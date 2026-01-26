// import Animations from '../modules/animations';
// import { slick } from 'slick-carousel';
// import 'slick-carousel/slick/slick.css';
// import 'slick-carousel/slick/slick-theme.css';
import Hero from '../hero';
import LogoGrid from '../logo-grid';
import SideBySide from '../side-by-side';

// --- State ---
let userPosition = null;
let map = null;
let markers = [];
let eventMarkers = [];
let allPlaces = [];
let allEvents = []; // Store events globally

const common = {
  init() {
    // --- Theme Management ---
    const toggleTheme = () => {
      const html = document.documentElement;
      const isDark = html.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      console.log('Theme toggled. Is dark:', isDark);
    };

    const applyInitialTheme = () => {
      const savedTheme = localStorage.getItem('theme') || 'light';
      if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    };

    applyInitialTheme();

    Hero.init();
    LogoGrid.init();
    SideBySide.init();

    $(document).on('click', '#theme-toggle, .theme-toggle-btn', toggleTheme);

    // --- GSAP Animations ---
    const tl = gsap.timeline();
    if ($('#landing-section').length) {
      tl.from("#landing-section img", { y: -50, opacity: 0, duration: 1, ease: "power3.out" })
        .from("#landing-section h1", { y: 30, opacity: 0, duration: 0.8 }, "-=0.5")
        .from("#main-search", { scale: 0.8, opacity: 0, duration: 0.5, clearProps: "opacity" }, "-=0.3")
        .from("#landing-section p", { y: 20, opacity: 0, duration: 0.5 }, "-=0.3");
    }

    // --- Search Interaction ---
    $('#search-btn').on('click', () => this.handleSearch());
    $('#main-search').on('keypress', (e) => {
      if (e.which == 13) this.handleSearch();
    });

    $('#header-search').on('keypress', (e) => {
      if (e.which == 13) {
        const query = $(e.target).val();
        this.filterPlacesByText(query);
      }
    });

    // --- Dropdown Logic ---
    $('#cat-dropdown-btn').on('click', function (e) {
      e.stopPropagation();
      $('#cat-dropdown-menu').toggleClass('hidden');
    });

    $(document).on('click', function (e) {
      if (!$(e.target).closest('.relative-dropdown').length) {
        $('#cat-dropdown-menu').addClass('hidden');
      }
    });

    // --- Multi-select Category logic (Dropdown) ---
    $('.category-filter').on('click', (e) => {
      e.stopPropagation(); // Keep dropdown open
      const btn = $(e.currentTarget);
      const category = btn.data('cat');

      if (category === 'all') {
        // Clear others and set All to active
        $('.category-filter').removeClass('bg-slate-100 dark:bg-slate-700 font-bold');
        $('.category-filter .check-icon').addClass('opacity-0'); // Hide all checks

        this.filterPlaces(['all']);
        // Maybe close dropdown on 'All'? Optional. Let's keep it open.
        $('#cat-dropdown-menu').addClass('hidden'); // Close on 'All' for convenience
      } else {
        // Toggle current
        const check = btn.find('.check-icon');
        if (check.hasClass('opacity-0')) {
          // Select
          check.removeClass('opacity-0').addClass('bg-brand-blue text-white border-transparent');
          btn.addClass('bg-slate-50 dark:bg-slate-700');
        } else {
          // Deselect
          check.addClass('opacity-0').removeClass('bg-brand-blue text-white border-transparent');
          btn.removeClass('bg-slate-50 dark:bg-slate-700');
        }

        // Collect all active
        const activeCats = [];
        $('.category-filter').each((i, el) => {
          const cat = $(el).data('cat');
          const isActive = !$(el).find('.check-icon').hasClass('opacity-0');
          if (cat !== 'all' && isActive) activeCats.push(cat);
        });

        if (activeCats.length === 0) {
          this.filterPlaces(['all']);
        } else {
          this.filterPlaces(activeCats);
        }
      }
    });

    // --- Events Toggle ---
    $('#toggle-events-btn').on('click', (e) => {
      const btn = $(e.currentTarget);
      const container = $('#events-container');

      if (container.hasClass('hidden')) {
        // Show
        container.removeClass('hidden').addClass('flex'); // Ensure flex is applied if needed, though Tailwind 'flex' class is already there
        btn.removeClass('bg-white border-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200')
          .addClass('bg-brand-gold text-white border-transparent');

        this.renderEventMarkers();

        // Animation
        gsap.fromTo(container, { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.4 });
      } else {
        // Hide
        gsap.to(container, {
          y: 20, opacity: 0, duration: 0.3, onComplete: () => {
            container.addClass('hidden').removeClass('flex');
          }
        });

        this.clearEventMarkers();

        btn.addClass('bg-white border-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200')
          .removeClass('bg-brand-gold text-white border-transparent');
      }
    });

    $(document).on('click', '.btn-directions', function (e) {
      e.stopPropagation();
      const placeName = $(this).data('name');
      const url = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(placeName + " San Cristóbal de las Casas")}`;
      window.open(url, '_blank');
    });

    $(document).on('click', '.btn-more-info', function (e) {
      e.stopPropagation();
      const id = $(this).data('id');
      const place = allPlaces.find(p => p.id === id);
      if (place && place.url) {
        window.location.href = place.url;
      }
    });
    // Favorite Button Logic
    $(document).on('click', '#btn-favorite', function () {
      if (!wpData.is_logged_in) {
        window.location.href = '/login';
        return;
      }

      const btn = $(this);
      const postId = btn.data('id');

      $.ajax({
        url: wpData.ajax_url,
        method: 'POST',
        data: {
          action: 'toggle_favorite',
          nonce: wpData.favorite_nonce,
          post_id: postId
        },
        success: (res) => {
          if (res.success) {
            if (res.data.action === 'added') {
              btn.text('❤️').removeClass('text-white opacity-50').addClass('text-red-500');
            } else {
              btn.text('🤍').addClass('text-white opacity-50').removeClass('text-red-500');
            }
          }
        },
        error: (err) => {
          console.error('Favorite request failed', err);
        }
      });
    });
  },

  handleSearch() {
    const query = $('#main-search').val();
    if (navigator.geolocation) {
      gsap.to("#landing-section", {
        y: -window.innerHeight,
        duration: 1,
        ease: "power4.inOut",
        onComplete: () => $("#landing-section").hide()
      });

      $("#map-section").removeClass('hidden').addClass('flex flex-col'); // Use Tailwind hidden class and restore flex
      gsap.fromTo("#map-section",
        { opacity: 0, y: window.innerHeight },
        { opacity: 1, y: 0, duration: 1, ease: "power4.inOut" }
      );

      // Hide fixed theme toggle as we have one in the header now
      $("#theme-toggle").fadeOut();

      this.initDirectory(query);
    } else {
      alert("Geolocation is not supported by this browser.");
    }
  },

  initDirectory(query) {
    $("#map-loader").removeClass('hidden');

    // Always center on San Cristóbal de las Casas
    const sanCristobalCenter = { lat: 16.7371, lng: -92.6376 };

    navigator.geolocation.getCurrentPosition(
      (position) => {
        // Store user position but center map on San Cristóbal
        userPosition = { lat: position.coords.latitude, lng: position.coords.longitude };
        this.renderMap(sanCristobalCenter);
        this.fetchWordPressPlaces();
      },
      () => {
        // If geolocation fails, still use San Cristóbal
        userPosition = sanCristobalCenter;
        this.renderMap(sanCristobalCenter);
        this.fetchWordPressPlaces();
      },
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  },

  fetchWordPressPlaces() {
    // Check for localized data first
    if (typeof wpData !== 'undefined' && wpData.places) {
      console.log('Using localized places data:', wpData.places.length);
      allPlaces = wpData.places;
      $("#map-loader").addClass('hidden');
      this.renderPlaces(allPlaces);
      this.renderEventsList();
      return;
    }

    $.ajax({
      url: '/wp-json/wp/v2/lugares?per_page=100&_embed',
      method: 'GET',
      success: (lugares) => {
        console.log('Lugares recibidos:', lugares.length);
        console.log('Primer lugar:', lugares[0]);

        allPlaces = lugares.map(lugar => {
          const featuredImage = lugar._embedded?.['wp:featuredmedia']?.[0]?.source_url || 'https://loremflickr.com/320/240/san-cristobal';
          const ubicacion = lugar.acf?.ubicacion;

          return {
            id: lugar.id,
            name: lugar.title.rendered,
            category: lugar.acf?.categoria || 'general',
            lat: parseFloat(ubicacion?.lat) || 16.7371,
            lng: parseFloat(ubicacion?.lng) || -92.6376,
            address: ubicacion?.address || '',
            description: lugar.acf?.descripcion_personalizada || lugar.excerpt?.rendered?.replace(/<[^>]*>/g, '').substring(0, 100) || 'Visita este increíble lugar en San Cristóbal.',
            image: featuredImage,
            url: lugar.link
          };
        });

        $("#map-loader").addClass('hidden');
        this.renderPlaces(allPlaces);
        this.renderEventsList();
      },
      error: () => {
        console.error('Error loading lugares from WordPress');
        $("#map-loader").addClass('hidden');
        allPlaces = [];
        this.renderPlaces(allPlaces);
        this.renderEventsList(); // Also render events even if places fail
      }
    });
  },

  renderMap(center) {
    if (map) return;
    // Initialize map with zoomControl false as we have custom controls
    map = L.map('map', { zoomControl: false }).setView([center.lat, center.lng], 14);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; CARTO'
    }).addTo(map);

    // Wire up Custom Controls
    $('#zoom-in').on('click', () => map.zoomIn());
    $('#zoom-out').on('click', () => map.zoomOut());
    $('#center-map').on('click', () => {
      if (userPosition) {
        map.flyTo([userPosition.lat, userPosition.lng], 16, { animate: true, duration: 1.5 });
      }
    });

    const userIcon = L.divIcon({
      className: 'custom-div-icon',
      html: '<div style="background-color: #3b82f6; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
      iconSize: [24, 24], iconAnchor: [12, 12], popupAnchor: [0, -15]
    });

    const userMarker = L.marker([center.lat, center.lng], { icon: userIcon }).addTo(map);
    userMarker.bindPopup("<b>Estás aquí</b>").openPopup();

    // Auto-close popup after 1.5 seconds
    setTimeout(() => {
      userMarker.closePopup();
    }, 1500);

    // Reduced radius from 2000 to 500
    L.circle([center.lat, center.lng], { color: '#d97706', fillOpacity: 0.1, radius: 500 }).addTo(map);
  },

  renderPlaces(places) {
    markers.forEach(m => map.removeLayer(m));
    markers = [];
    const container = $('#cards-container');
    container.empty();
    $('#result-count-desktop').text(places.length);

    const getIcon = (place) => {
      const emojis = { restaurant: '🍽️', park: '🌳', museum: '🏛️', shop: '🛍️' };
      const iconChar = place.icon || emojis[place.category] || '📍';
      return L.divIcon({
        className: 'custom-marker',
        html: `<div class="flex items-center justify-center w-10 h-10 bg-white dark:bg-slate-800 rounded-full shadow-lg border-2 border-brand-blue text-xl">${iconChar}</div>`,
        iconSize: [40, 40], iconAnchor: [20, 40], popupAnchor: [0, -60]
      });
    };

    const markerMap = {};
    places.forEach(place => {
      const marker = L.marker([place.lat, place.lng], { icon: getIcon(place) })
        .addTo(map)
        .bindPopup(`
          <div class="min-w-[200px]">
            <img src="${place.image}" class="w-full h-32 object-cover rounded-t-lg mb-2" alt="${place.name}">
            <h3 class="font-bold text-lg text-brand-blue leading-tight">${place.name}</h3>
            <div class="text-[10px] font-bold text-slate-500 uppercase mb-1">${place.label || place.category}</div>
            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 mb-3">${place.description}</p>
            <div class="grid grid-cols-2 gap-2">
                <button class="bg-brand-blue text-white text-[10px] font-bold py-2 rounded-lg btn-more-info" data-id="${place.id}">Conoce más</button>
                <button class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-bold py-2 rounded-lg btn-directions" data-name="${place.name}">Cómo llegar</button>
            </div>
          </div>
        `, { maxWidth: 250, className: 'custom-popup' });

      markers.push(marker);
      markerMap[place.id] = marker;

      const card = `
        <div class="flex-shrink-0 w-[85vw] md:w-full bg-white dark:bg-slate-800 p-4 rounded-xl shadow-lg md:shadow-sm border border-slate-200 md:border-slate-100 dark:border-slate-700 hover:shadow-xl transition-all cursor-pointer card-item snap-center" data-id="${place.id}">
          <div class="flex gap-4">
            <img src="${place.image}" class="w-24 h-24 rounded-lg object-cover">
            <div class="flex-grow min-w-0">
               <h4 class="font-bold text-brand-blue dark:text-brand-gold truncate">${place.name}</h4>
               <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2">${place.description}</p>
               <div class="mt-2 flex justify-between">
                  <button class="text-brand-blue dark:text-brand-gold font-bold text-xs btn-directions" data-name="${place.name}">Llegar</button>
                  <button class="text-brand-blue dark:text-brand-gold font-bold text-xs btn-more-info" data-id="${place.id}">Ver más →</button>
               </div>
            </div>
          </div>
        </div>
      `;
      container.append(card);
    });

    $('.card-item').on('click', function (e) {
      if ($(e.target).closest('button').length) return;
      const id = $(this).data('id');
      const place = places.find(p => p.id === id);
      if (place) {
        map.flyTo([place.lat, place.lng], 16, { animate: true, duration: 1.5 });
        markerMap[id].openPopup();
      }
    });
  },

  generateMockPlaces(lat, lng, count = 15) {
    const categories = ['restaurant', 'park', 'museum', 'shop'];
    const places = [];
    for (let i = 0; i < count; i++) {
      const category = categories[Math.floor(Math.random() * categories.length)];
      places.push({
        id: i,
        name: `Lugar Mock ${category} ${i + 1}`,
        category: category,
        lat: lat + (Math.random() - 0.5) * 0.036,
        lng: lng + (Math.random() - 0.5) * 0.036,
        description: `Un lugar excelente para visitar en San Cris.`,
        image: `https://loremflickr.com/320/240/${category},san-cristobal?random=${i}`
      });
    }
    return places;
  },

  filterPlaces(categories) {
    if (categories === 'all' || (Array.isArray(categories) && categories.includes('all'))) {
      this.renderPlaces(allPlaces);
    } else {
      // Ensure categories is array
      const cats = Array.isArray(categories) ? categories : [categories];
      this.renderPlaces(allPlaces.filter(p => cats.includes(p.category)));
    }
  },

  filterPlacesByText(text) {
    if (!text) return this.renderPlaces(allPlaces);
    const lowerText = text.toLowerCase();
    this.renderPlaces(allPlaces.filter(p =>
      p.name.toLowerCase().includes(lowerText) ||
      p.description.toLowerCase().includes(lowerText)
    ));
  },

  renderEventsList() {
    const container = $('#events-container');
    container.empty().append('<h3 class="text-3xl font-black text-brand-gold italic">HOY</h3>');

    const renderEvents = (eventos) => {
      if (!eventos || eventos.length === 0) {
        container.append('<p class="text-slate-400 text-sm mt-4">No hay eventos próximos</p>');
        return;
      }

      eventos.forEach((evento, index) => {
        const fecha = evento.acf?.fecha || 'Próximamente';
        const hora = evento.acf?.hora || '';
        const ubicacion = evento.acf?.ubicacion || '';
        const datetime = hora ? `${fecha}, ${hora}` : fecha;
        const title = evento.title.rendered || evento.title; // Handle both API and localized format
        const link = evento.link || '#';

        const card = $(`
          <div class="bg-white/95 dark:bg-slate-800/95 p-3 rounded-xl shadow-xl opacity-0 cursor-pointer hover:scale-105 transition-transform" data-url="${link}">
            <h4 class="font-bold text-brand-blue dark:text-white text-sm truncate">${title}</h4>
            <p class="text-[10px] text-slate-500">${datetime} • ${ubicacion}</p>
          </div>
        `);

        card.on('click', function () {
          window.location.href = $(this).data('url');
        });

        container.append(card);
        gsap.to(card, { opacity: 1, x: 0, from: { x: 20 }, delay: index * 0.15, duration: 0.6 });
      });
    };

    if (typeof wpData !== 'undefined' && wpData.events) {
      allEvents = wpData.events; // Store globally
      renderEvents(wpData.events);
      return;
    }

    $.ajax({
      url: '/wp-json/wp/v2/eventos?per_page=10&_embed',
      method: 'GET',
      success: (eventos) => {
        allEvents = eventos; // Store globally
        renderEvents(eventos);
      },
      error: () => {
        console.error('Error loading eventos from WordPress');
        container.append('<p class="text-slate-400 text-sm mt-4">Error al cargar eventos</p>');
      }
    });
  },

  renderEventMarkers() {
    if (!allEvents || allEvents.length === 0) return;

    const getEventIcon = () => {
      return L.divIcon({
        className: 'custom-marker-event',
        html: `<div class="flex items-center justify-center w-10 h-10 bg-brand-gold rounded-full shadow-lg border-2 border-white text-xl text-white">📅</div>`,
        iconSize: [40, 40], iconAnchor: [20, 40], popupAnchor: [0, -60]
      });
    };

    allEvents.forEach(event => {
      // Check for lat/lng (mapped from wpData or API)
      let lat, lng;

      if (event.lat && event.lng) {
        // From wpData
        lat = event.lat;
        lng = event.lng;
      } else if (event.acf && event.acf.ubicacion_mapa) {
        // From API (if field is exposed, but we didn't expose it yet in scripts.php for API, only localized)
        // Since we are primarily using wpData for map, this is fine.
        // If we fetched via AJAX, we need to ensure we map it.
        // For now, let's rely on wpData or mapped data.
        // Actually, I updated scripts.php. 
        // But if using AJAX, I didn't update the REST API response manually, 
        // unless ACF exposes it in 'acf' object in JSON.
        // 'ubicacion_mapa' should be in 'acf'.
        lat = parseFloat(event.acf.ubicacion_mapa.lat);
        lng = parseFloat(event.acf.ubicacion_mapa.lng);
      }

      if (lat && lng) {
        const marker = L.marker([lat, lng], { icon: getEventIcon() })
          .addTo(map)
          .bindPopup(`
                      <div class="min-w-[200px]">
                          <h3 class="font-bold text-lg text-brand-gold leading-tight mb-2">${event.title.rendered || event.title}</h3>
                          <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">📅 ${event.acf.fecha || ''} ${event.acf.hora || ''}</p>
                          <a href="${event.link}" class="bg-brand-gold text-white text-[10px] font-bold py-2 px-4 rounded-lg inline-block">Ver Evento</a>
                      </div>
                  `, { maxWidth: 250, className: 'custom-popup' });

        eventMarkers.push(marker);
      }
    });
  },

  clearEventMarkers() {
    eventMarkers.forEach(marker => map.removeLayer(marker));
    eventMarkers = [];
  },

  finalize() { }
};
export default common;