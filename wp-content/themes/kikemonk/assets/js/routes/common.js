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
let tileLayer = null;
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
      this.updateMapTheme(isDark);
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

    // --- Mobile Search Toggle ---
    $('#mobile-search-toggle').on('click', () => {
      const container = $('#header-search-container');
      container.toggleClass('hidden');
      if (!container.hasClass('hidden')) {
        setTimeout(() => $('#header-search').focus(), 100);
      }
    });

    // Close search on click outside (mobile)
    $(document).on('click', (e) => {
      if (window.innerWidth < 768) { // md breakpoint
        if (!$(e.target).closest('#header-search-container').length && !$(e.target).closest('#mobile-search-toggle').length) {
          $('#header-search-container').addClass('hidden');
        }
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
    // --- Events Toggle ---
    $('#toggle-events-btn').on('click', (e) => {
      const btn = $(e.currentTarget);
      const container = $('#events-container');
      const cardsWrapper = $('#cards-container').parent().parent(); // Targeted wrapper

      if (container.hasClass('hidden')) {
        // Show
        container.removeClass('hidden').addClass('flex');
        btn.removeClass('bg-white border-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200')
          .addClass('bg-brand-gold text-white border-transparent');

        // On mobile, hide cards to prevent overlap
        if (window.innerWidth < 768) {
          gsap.to(cardsWrapper, { y: 100, opacity: 0, duration: 0.3, onComplete: () => cardsWrapper.addClass('hidden') });
        }

        this.renderEventMarkers();

        // Animation
        gsap.fromTo(container, { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.4 });
      } else {
        // Hide
        gsap.to(container, {
          y: 20, opacity: 0, duration: 0.3, onComplete: () => {
            container.addClass('hidden').removeClass('flex');

            // Restore Cards
            if (cardsWrapper.hasClass('hidden')) {
              cardsWrapper.removeClass('hidden');
              gsap.to(cardsWrapper, { y: 0, opacity: 1, duration: 0.3 });
            }
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
        window.location.href = '/registro';
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
    // Favorite Button Logic (Popup)
    $(document).on('click', '.btn-favorite-popup', function (e) {
      e.stopPropagation();
      if (!wpData.is_logged_in) {
        window.location.href = '/registro';
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
              btn.text('❤️').removeClass('text-slate-400 dark:text-white opacity-50').addClass('text-red-500');
              // Update local store to reflect change immediately if re-opened
              if (wpData.favorites) wpData.favorites.push(postId);
            } else {
              btn.text('🤍').addClass('text-slate-400 dark:text-white opacity-50').removeClass('text-red-500');
              if (wpData.favorites) {
                const idx = wpData.favorites.indexOf(postId);
                if (idx > -1) wpData.favorites.splice(idx, 1);
                const idxStr = wpData.favorites.indexOf(String(postId));
                if (idxStr > -1) wpData.favorites.splice(idxStr, 1);
              }
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

    // Always trigger the transition
    gsap.to("#landing-section", {
      y: -window.innerHeight,
      duration: 1,
      ease: "power4.inOut",
      onComplete: () => $("#landing-section").hide()
    });

    $("#map-section").removeClass('hidden').addClass('flex flex-col');
    gsap.fromTo("#map-section",
      { opacity: 0, y: window.innerHeight },
      { opacity: 1, y: 0, duration: 1, ease: "power4.inOut" }
    );

    // Hide fixed theme toggle as we have one in the header now
    $("#theme-toggle").fadeOut();

    this.initDirectory(query);
  },

  initDirectory(query) {
    $("#map-loader").removeClass('hidden');

    // Always center on San Cristóbal de las Casas
    const sanCristobalCenter = { lat: 16.7371, lng: -92.6376 };

    const handleSuccess = (position) => {
      // Store user position and center map on it
      userPosition = { lat: position.coords.latitude, lng: position.coords.longitude };
      this.renderMap(userPosition);
      this.fetchWordPressPlaces();
    };

    const handleError = (error) => {
      console.warn("Location access denied or error: ", error.message || error);
      // Silently fallback to default
      useDefault();
    };

    const useDefault = () => {
      userPosition = sanCristobalCenter;
      this.renderMap(sanCristobalCenter);
      this.fetchWordPressPlaces();
    };

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        handleSuccess,
        handleError,
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
      );
    } else {
      // Browser doesn't support geolocation, fallback immediately
      console.warn("Geolocation not supported by this browser.");
      useDefault();
    }
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

    const isDark = document.documentElement.classList.contains('dark');
    const lightUrl = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    const darkUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

    tileLayer = L.tileLayer(isDark ? darkUrl : lightUrl, {
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
      const isFav = wpData.favorites && (wpData.favorites.includes(place.id) || wpData.favorites.includes(String(place.id)));
      const heartChar = isFav ? '❤️' : '🤍';
      const heartClass = isFav ? 'text-red-500' : 'text-slate-400 dark:text-white';

      const marker = L.marker([place.lat, place.lng], { icon: getIcon(place) })
        .addTo(map)
        .bindPopup(`
          <div class="min-w-[220px] relative">
            <button class="absolute top-2 right-2 z-10 p-1.5 bg-white/90 dark:bg-slate-800/90 rounded-full shadow-md btn-favorite-popup ${heartClass} hover:scale-110 transition-transform" data-id="${place.id}" title="Agregar a Favoritos">${heartChar}</button>
            <img src="${place.image}" class="w-full h-32 object-cover rounded-t-lg mb-2 bg-slate-100 dark:bg-slate-700" alt="${place.name}">
            <h3 class="font-bold text-lg text-brand-blue leading-tight pr-6">${place.name}</h3>
            <div class="text-[10px] font-bold text-slate-500 uppercase mb-1">${place.label || place.category}</div>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-3 max-h-32 overflow-y-auto pr-1 custom-scrollbar">${place.description}</p>
            <div class="grid grid-cols-2 gap-2">
                <button class="bg-brand-blue text-white text-[10px] font-bold py-2 rounded-lg btn-more-info" data-id="${place.id}">Conoce más</button>
                <button class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-bold py-2 rounded-lg btn-directions" data-name="${place.name}">Cómo llegar</button>
            </div>
          </div>
        `, { maxWidth: 260, className: 'custom-popup' });

      markers.push(marker);
      markerMap[place.id] = marker;

      const card = `
        <div class="flex-shrink-0 w-[85vw] md:w-full bg-white dark:bg-slate-800 p-4 rounded-xl shadow-lg md:shadow-sm border border-slate-200 md:border-slate-100 dark:border-slate-700 hover:shadow-xl transition-all cursor-pointer card-item snap-center" data-id="${place.id}">
          <div class="flex gap-4">
            <img src="${place.image}" class="w-24 h-24 rounded-lg object-cover aspect-square flex-shrink-0">
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

    if (markers.length > 0) {
      const group = L.featureGroup(markers);
      map.fitBounds(group.getBounds(), { padding: [50, 50], maxZoom: 16, animate: true, duration: 1 });
    }

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
      this.renderPlaces(allPlaces.filter(p => {
        // Check against 'categories' array if present (new logic)
        if (p.categories && Array.isArray(p.categories) && p.categories.length > 0) {
          return p.categories.some(c => cats.includes(c));
        }
        // Fallback to single category
        return cats.includes(p.category);
      }));
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
    container.empty();

    // Header/Status Card
    const headerCard = $('<div class="p-4 pb-2"></div>');
    headerCard.append('<h3 class="text-xl font-black text-brand-gold italic leading-none uppercase">Próximos eventos</h3>');
    container.append(headerCard);

    const renderEvents = (eventos) => {
      if (!eventos || eventos.length === 0) {
        headerCard.append('<p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-2">No hay eventos próximos</p>');
        return;
      }

      const months = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];

      eventos.forEach((evento, index) => {
        const rawDate = evento.acf?.fecha; // Expecting YYYYMMDD
        let day = '??';
        let month = '???';

        if (rawDate && rawDate.length === 8) {
          const monthIdx = parseInt(rawDate.substring(4, 6)) - 1;
          day = rawDate.substring(6, 8);
          month = months[monthIdx] || '???';
        }

        const hora = evento.acf?.hora || '';
        const ubicacion = evento.acf?.ubicacion || '';
        const title = evento.title.rendered || evento.title;
        const link = evento.link || '#';
        // Get excerpt without HTML tags and limit length
        const rawExcerpt = evento.excerpt?.rendered || evento.excerpt || '';
        const excerpt = rawExcerpt.replace(/<[^>]*>/g, '').substring(0, 60) + (rawExcerpt.length > 60 ? '...' : '');

        const card = $(`
          <div class="bg-transparent p-3 rounded-xl opacity-0 cursor-pointer transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 w-full" data-url="${link}">
            <div class="flex gap-3 items-start">
                 <!-- Date Box -->
                 <div class="flex-shrink-0 w-14 h-14 bg-brand-gold/10 dark:bg-brand-gold/20 rounded-lg flex flex-col items-center justify-center text-brand-gold border border-brand-gold/20">
                     <span class="text-[10px] font-bold uppercase leading-none mb-0.5">${month}</span>
                     <span class="text-2xl font-black leading-none">${day}</span>
                 </div>
                 
                 <!-- Content -->
                 <div class="flex-grow min-w-0">
                    <h4 class="font-bold text-brand-blue dark:text-white text-xs line-clamp-2 leading-tight mb-1" title="${title}">${title}</h4>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-snug line-clamp-2 mb-2">${excerpt}</p>
                    
                    <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                        ${hora ? `<span>🕒 ${hora}</span>` : ''}
                        ${hora && ubicacion ? '<span class="opacity-30">•</span>' : ''}
                        ${ubicacion ? `<span class="truncate">📍 ${ubicacion}</span>` : ''}
                    </div>
                 </div>
            </div>
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
        const isFav = wpData.favorites && (wpData.favorites.includes(event.id) || wpData.favorites.includes(String(event.id)));
        const heartChar = isFav ? '❤️' : '🤍';
        const heartClass = isFav ? 'text-red-500' : 'text-slate-400 dark:text-white';

        const marker = L.marker([lat, lng], { icon: getEventIcon() })
          .addTo(map)
          .bindPopup(`
                      <div class="min-w-[220px] relative">
                          <button class="absolute top-2 right-2 z-10 p-1.5 bg-white/90 dark:bg-slate-800/90 rounded-full shadow-md btn-favorite-popup ${heartClass} hover:scale-110 transition-transform" data-id="${event.id}" title="Agregar a Favoritos">${heartChar}</button>
                          <h3 class="font-bold text-lg text-brand-gold leading-tight mb-2 pr-6">${event.title.rendered || event.title}</h3>
                          <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">📅 ${event.acf.fecha || ''} ${event.acf.hora || ''}</p>
                          <p class="text-[10px] font-bold mb-2 ${event.acf.costo_tipo === 'paid' ? 'text-brand-blue dark:text-brand-gold' : 'text-green-500'}">
                              ${event.acf.costo_tipo === 'paid' ? '🎟️ ' + (event.acf.costo_valor || 'Con Costo') : '🎟️ Entrada Libre'}
                          </p>
                          <p class="text-[10px] text-slate-500 mb-3 max-h-24 overflow-y-auto custom-scrollbar">${event.acf.ubicacion || ''}</p>
                          <a href="${event.link}" class="bg-brand-gold text-white text-[10px] font-bold py-2 px-4 rounded-lg inline-block">Ver Evento</a>
                      </div>
                  `, { maxWidth: 260, className: 'custom-popup' });

        eventMarkers.push(marker);
      }
    });
  },

  clearEventMarkers() {
    eventMarkers.forEach(marker => map.removeLayer(marker));
    eventMarkers = [];
  },

  updateMapTheme(isDark) {
    if (!tileLayer) return;
    const url = isDark
      ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
      : 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    tileLayer.setUrl(url);
  },



  finalize() { }
};
export default common;