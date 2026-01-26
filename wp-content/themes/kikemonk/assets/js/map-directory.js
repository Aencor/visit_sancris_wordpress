$(document).ready(function () {
  const container = $('#map-directory-block');
  if (!container.length) return;

  // --- State ---
  let map = null;
  let markers = [];
  let allPlaces = [];

  // --- Theme Management ---
  const applyTheme = (theme) => {
    if (theme === 'dark') {
      $('html').addClass('dark');
      localStorage.setItem('theme', 'dark');
    } else {
      $('html').removeClass('dark');
      localStorage.setItem('theme', 'light');
    }
  };

  const savedTheme = localStorage.getItem('theme') || 'light';
  applyTheme(savedTheme);

  $('#theme-toggle').on('click', function () {
    const isDark = $('html').hasClass('dark');
    applyTheme(isDark ? 'light' : 'dark');
  });

  // --- Map & Logic ---
  function initMap(center) {
    map = L.map('map-instance').setView([center.lat, center.lng], 15);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const userIcon = L.divIcon({
      className: 'custom-div-icon',
      html: '<div style="background-color: #3b82f6; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
      iconSize: [24, 24],
      iconAnchor: [12, 12],
      popupAnchor: [0, -15]
    });

    L.marker([center.lat, center.lng], { icon: userIcon }).addTo(map)
      .bindPopup('Estás aquí').openPopup();

    L.circle([center.lat, center.lng], {
      color: '#d97706',
      fillColor: '#d97706',
      fillOpacity: 0.1,
      radius: 1000
    }).addTo(map);
  }

  function renderPlaces(places) {
    markers.forEach(m => map.removeLayer(m));
    markers = [];
    const listContainer = $('#cards-container-wp');
    listContainer.empty();

    const getIcon = (category) => {
      const emojis = { restaurant: '🍽️', park: '🌳', museum: '🏛️', shop: '🛍️' };
      return L.divIcon({
        className: 'custom-marker',
        html: `<div class="flex items-center justify-center w-10 h-10 bg-white dark:bg-slate-800 rounded-full shadow-lg border-2 border-brand-blue text-xl">${emojis[category] || '📍'}</div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -60]
      });
    };

    const markerMap = {};
    places.forEach(place => {
      const marker = L.marker([place.lat, place.lng], { icon: getIcon(place.category) })
        .addTo(map)
        .bindPopup(`
          <div class="min-w-[200px]">
            <img src="${place.image}" class="w-full h-32 object-cover rounded-t-lg mb-2" alt="${place.name}">
            <h3 class="font-bold text-lg text-brand-blue leading-tight">${place.name}</h3>
            <div class="text-[10px] font-bold text-slate-500 uppercase mb-1">${place.category}</div>
            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 mb-3">${place.description}</p>
            <div class="grid grid-cols-2 gap-2">
                <a href="${place.url}" class="bg-brand-blue text-white text-[10px] font-bold py-2 rounded-lg text-center hover:bg-blue-800 transition-colors">Conoce más</a>
                <button class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-bold py-2 rounded-lg btn-directions" data-name="${place.name}">Cómo llegar</button>
            </div>
          </div>
        `, { maxWidth: 250, className: 'custom-popup' });

      markers.push(marker);
      markerMap[place.id] = marker;

      const card = `
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-all cursor-pointer card-item" data-id="${place.id}">
          <div class="flex gap-4">
            <img src="${place.image}" alt="${place.name}" class="w-20 h-20 rounded-lg object-cover">
            <div class="flex-grow min-w-0">
               <h4 class="font-bold text-brand-blue dark:text-brand-gold truncate">${place.name}</h4>
               <p class="text-[10px] text-slate-500 mt-1 line-clamp-2">${place.description}</p>
               <div class="mt-2 flex gap-3 text-[10px] font-bold">
                 <button class="text-brand-blue dark:text-brand-gold hover:underline btn-directions" data-name="${place.name}">Llegar</button>
                 <a href="${place.url}" class="text-brand-blue dark:text-brand-gold hover:underline">Ver más →</a>
               </div>
            </div>
          </div>
        </div>
      `;
      listContainer.append(card);
    });

    $('.card-item').on('click', function () {
      const id = $(this).data('id');
      const place = places.find(p => p.id === id);
      if (place) {
        map.flyTo([place.lat, place.lng], 16, { animate: true, duration: 1.5 });
        markerMap[id].openPopup();
      }
    });
  }

  // --- Initial Data ---
  // In a real WP scenario, we'd fetch this from the localized object
  if (typeof wpData !== 'undefined' && wpData.places) {
    allPlaces = wpData.places;
    initMap(wpData.center);
    renderPlaces(allPlaces);
  }

  $(document).on('click', '.btn-directions', function (e) {
    e.stopPropagation();
    const name = $(this).data('name');
    window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(name + " San Cristóbal")}`, '_blank');
  });
});
