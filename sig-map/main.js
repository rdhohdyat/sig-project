import { Map, View } from "ol";
import TileLayer from "ol/layer/Tile";
import OSM from "ol/source/OSM";
import { fromLonLat } from "ol/proj.js";
import { Vector as VectorSource } from "ol/source.js";
import VectorLayer from "ol/layer/Vector";
import { Icon, Style, Fill, Stroke } from "ol/style.js";
import Overlay from "ol/Overlay.js";
import Feature from "ol/Feature";
import { Point } from "ol/geom.js";

const map = new Map({
  target: "map",
  layers: [
    new TileLayer({
      source: new OSM(),
    }),
  ],
  view: new View({
    center: fromLonLat([101.4609, 0.55044]),
    zoom: 12,
  }),
});

const container = document.getElementById("popup");
const popupCloser = document.getElementById("popup-closer");
const popupContent = document.getElementById("popup-content");

// const overlay = new Overlay({
//   element: container,
//   autoPan: {
//     animation: {
//       duration: 250,
//     },
//   },
// });

let fasilitasLayer = new VectorLayer({
  source: new VectorSource(),
  // style: new Style({
  //   image: new Icon({
  //     anchor: [0.5, 1],
  //     src: "icon/location-pin.png",
  //     width: 32,
  //     height: 32,
  //   }),
  // }),
});




map.addLayer(fasilitasLayer);

document.getElementById("search-input").addEventListener("input", function (e) {
  const searchValue = e.target.value.toLowerCase();
  filterFacilities(searchValue);
});

const filterFacilities = (searchValue) => {
  fasilitasLayer.getSource().clear();
  fasilitasContainer.innerHTML = "";

  const filteredFacilities = allFacilities.filter(
    (facility) =>
      facility.nama.toLowerCase().includes(searchValue) ||
      facility.kategori.toLowerCase().includes(searchValue)
  );

  jumlahFasilitas.innerHTML = `Jumlah: ${filteredFacilities.length}`;

  if (filteredFacilities.length < 1) {
    fasilitasContainer.innerHTML =
      "<p class='text-center font-semibold'>Fasilitas tidak ditemukan!</p>";
  } else {
    filteredFacilities.forEach((item) => {
      const latitude = parseFloat(item.latitude);
      const longitude = parseFloat(item.longitude);
      const koordinat = fromLonLat([longitude, latitude]);

      const marker = new Feature({
        geometry: new Point(koordinat),
        nama: item.nama,
        alamat: item.alamat,
        kategori: item.kategori,
        jam_buka: item.jam_buka,
        jam_tutup: item.jam_tutup,
        foto: item.foto
      });

      marker.setStyle(
        new Style({
          image: new Icon({
            anchor: [0.5, 1],
            src: getIconForCategory(item.kategori),
            scale: 0.05,
          }),
        })
      );

      fasilitasLayer.getSource().addFeature(marker);

      const card = document.createElement("div");
      card.className =
        "bg-white shadow-md w-full rounded-lg overflow-hidden transition-transform transform hover:shadow-xl mb-4 cursor-pointer";
      card.innerHTML = `
        <img src="${item.foto}" class="w-full h-[170px] object-cover" alt="${item.nama
        }" />
        <div class="p-4">
          <h1 class="font-bold text-lg text-sky-900 mb-2">${item.nama}</h1>
          <p class="text-sm font-semibold text-gray-800">Jam Operasional: ${item.jam_buka
        } - ${item.jam_tutup}</p>
          <p class="text-sm text-gray-500 mt-2">Lokasi: ${item.kecamatan}</p>
        </div>
      `;

      card.addEventListener("click", () => {
        document.getElementById(
          "modal-image"
        ).src = `${item.foto}`;
        document.getElementById("modal-nama").innerText = item.nama;
        document.getElementById("modal-deskripsi").innerText = item.kategori;
        document.getElementById("modal-jamBuka").innerText =
          "Jam Buka: " + item.jam_buka;
        document.getElementById("modal-lokasi").innerText =
          "Lokasi: " + item.alamat;

        document.getElementById("modal").classList.remove("hidden");
      });

      fasilitasContainer.appendChild(card);
    });
  }
};

document.getElementById("apply-time-filter").addEventListener("click", (e) => {
  e.preventDefault();
  const jamBukaFilter = document.getElementById("jam-buka").value;
  const jamTutupFilter = document.getElementById("jam-tutup").value;

  const selectedCategory = document.querySelector(".category_item.active")?.getAttribute("data-category") || "";
  const selectedKecamatan = document.getElementById("kecamatan_options").value;

  filterByCategoryKecamatanAndTime(selectedCategory, selectedKecamatan, jamBukaFilter, jamTutupFilter);
});

const filterByCategoryAndKecamatan = (category, kecamatan) => {
  const jamBukaFilter = document.getElementById("jam-buka").value || "12:00 AM";
  const jamTutupFilter = document.getElementById("jam-tutup").value || "11:59 PM";

  filterByCategoryKecamatanAndTime(category, kecamatan, jamBukaFilter, jamTutupFilter);
};



let allFacilities = [];

let jumlahFasilitas = document.getElementById("jumlah_fasilitas");
let fasilitasContainer = document.getElementById("fasilitas-container");

const getIconForCategory = (kategori) => {
  switch (kategori) {
    case "Taman":
      return "icon/taman.png";
    case "Tempat Ibadah":
      return "icon/location-pin.png";
    case "Tempat Belanja":
      return "icon/tempat_belanja.png";
    case "Halte Bus":
      return "icon/halte_bus.png";
    default:
      return "icon/location-pin.png";
  }
};

const fetchLocation = async () => {
  fasilitasContainer.innerHTML = ``;
  try {
    const response = await fetch("http://localhost:8000/fasilitas");
    const data = await response.json();

    jumlahFasilitas.innerHTML = `Jumlah : ${data.length}`;

    allFacilities = data;

    data.forEach((item) => {
      const latitude = parseFloat(item.latitude);
      const longitude = parseFloat(item.longitude);
      const koordinat = fromLonLat([longitude, latitude]);

      const marker = new Feature({
        geometry: new Point(koordinat),
        nama: item.nama,
        alamat: item.alamat,
        kategori: item.kategori,
        jam_buka: item.jam_buka,
        jam_tutup: item.jam_tutup,
        foto: item.foto
      });

      marker.setStyle(
        new Style({
          image: new Icon({
            anchor: [0.5, 1],
            src: getIconForCategory(item.kategori),
            scale: 0.05,
          }),
        })
      );

      fasilitasLayer.getSource().addFeature(marker);
      const card = document.createElement("div");
      card.className =
        "bg-white shadow-md w-full rounded-lg overflow-hidden transition-transform transform hover:shadow-xl mb-4 cursor-pointer";

      card.innerHTML = `
      <img src="${item.foto}" class="w-full h-[170px] object-cover" alt="${item.nama
        }" />
      <div class="p-4">
        <h1 class="font-bold text-lg text-sky-900 mb-2">${item.nama}</h1>
        <p class="text-sm font-semibold text-gray-800">Jam Operasional : ${item.jam_buka
        } - ${item.jam_tutup}</p>
        <p class="text-sm text-gray-500 mt-2">Lokasi: ${item.kecamatan}</p>
      </div>
    `;

      card.addEventListener("click", () => {
        document.getElementById(
          "modal-image"
        ).src = `${item.foto}`;
        document.getElementById("modal-nama").innerText = item.nama;
        document.getElementById("modal-deskripsi").innerText = item.kategori;
        document.getElementById("modal-jamBuka").innerText =
          "Jam Buka: " + item.jam_buka;
        document.getElementById("modal-lokasi").innerText =
          "Lokasi: " + item.alamat;

        document.getElementById("modal").classList.remove("hidden");
      });

      fasilitasContainer.appendChild(card);
    });

  } catch (error) {
    console.error("Error fetching facility data:", error);
  }
};

let popupContainer = document.getElementById("test-popup")

const popup = new Overlay({
  element: popupContainer,
  positioning: 'top-center', // Adjust positioning as needed
  stopEvent: false, // Allow map interaction
  offset: [0, -10] // Adjust offset based on your needs
});

map.addOverlay(popup)

map.on('singleclick', function (evt) {
  const feature = map.forEachFeatureAtPixel(evt.pixel, function (feat) {
    return feat;
  });

  popupContainer.classList.toggle("hidden");

  if (feature) {
    const coordinates = feature.getGeometry().getCoordinates();
    popupContainer.innerHTML = `
      <img class="w-full h-[200px] object-cover" src=${feature.get("foto")}>
      <h3>Informasi Fasilitas</h3>
      <p>Nama: <strong>${feature.get("nama")}</strong></p>
      <p>Alamat: ${feature.get("alamat")}</p>
      <p>Kategori: ${feature.get("kategori")}</p>
      <p>Jam Operasional: ${feature.get("jam_buka")} - ${feature.get("jam_tutup")}</p>
    `;

    popup.setPosition(coordinates);
    
  } else {
    // Hide the popup if no feature was clicked
    popup.setPosition(undefined);
    popupContainer.classList.add("hidden");
  }
});


document.querySelectorAll('.overlay-container input[type="checkbox"]').forEach((checkbox) => {
  checkbox.addEventListener('change', (e) => {
    const category = e.target.dataset.category;
    if (e.target.checked) {
      showCategory(category);
    } else {
      hideCategory(category);
    }
  });
});

const showCategory = (category) => {
  allFacilities.forEach((facility) => {
    if (facility.kategori === category) {
      const latitude = parseFloat(facility.latitude);
      const longitude = parseFloat(facility.longitude);
      const koordinat = fromLonLat([longitude, latitude]);

      const marker = new Feature({
        geometry: new Point(koordinat),
        nama: facility.nama,
        alamat: facility.alamat,
        kategori: facility.kategori,
        jam_buka: facility.jam_buka,
        jam_tutup: facility.jam_tutup,
        foto: facility.foto
      });

      marker.setStyle(
        new Style({
          image: new Icon({
            anchor: [0.5, 1],
            src: getIconForCategory(facility.kategori),
            scale: 0.05,
          }),
        })
      );

      fasilitasLayer.getSource().addFeature(marker);
    }
  });
};

const hideCategory = (category) => {
  const featuresToRemove = fasilitasLayer
    .getSource()
    .getFeatures()
    .filter((feature) => feature.get('kategori') === category);

  featuresToRemove.forEach((feature) => {
    fasilitasLayer.getSource().removeFeature(feature);
  });
};

fetchLocation();


const parseTime = (timeString) => {
  if (!timeString) return null;
  const [time, meridian] = timeString.trim().toUpperCase().split(" ");
  const [hours, minutes] = time.split(":").slice(0, 2).map(Number);

  const adjustedHours = meridian === "PM" && hours !== 12
    ? hours + 12
    : meridian === "AM" && hours === 12
    ? 0
    : hours;

  return adjustedHours * 60 + minutes;
};


const filterByCategoryKecamatanAndTime = (category, kecamatan, jamBukaFilter, jamTutupFilter) => {
  fasilitasLayer.getSource().clear();
  fasilitasContainer.innerHTML = "";

  const filteredFacilities = allFacilities.filter((fasilitas) => {
    const fasilitasJamBuka = parseTime(fasilitas.jam_buka);
    const fasilitasJamTutup = parseTime(fasilitas.jam_tutup);
    const filterJamBuka = parseTime(jamBukaFilter);
    const filterJamTutup = parseTime(jamTutupFilter);

    const isWithinTimeRange =
      fasilitasJamBuka >= filterJamBuka &&
      fasilitasJamTutup <= filterJamTutup;

    const isCategoryMatch = !category || fasilitas.kategori === category;
    const isKecamatanMatch = !kecamatan || fasilitas.kecamatan === kecamatan;

    return isWithinTimeRange && isCategoryMatch && isKecamatanMatch;
  });

  jumlahFasilitas.innerHTML = `Jumlah: ${filteredFacilities.length}`;

  if (filteredFacilities.length < 1) {
    fasilitasContainer.innerHTML =
      "<p class='text-center font-semibold'>Fasilitas tidak ditemukan!</p>";
  } else {
    filteredFacilities.forEach((item) => {
      const latitude = parseFloat(item.latitude);
      const longitude = parseFloat(item.longitude);
      const koordinat = fromLonLat([longitude, latitude]);

      const marker = new Feature({
        geometry: new Point(koordinat),
        nama: item.nama,
        alamat: item.alamat,
        kategori: item.kategori,
        jam_buka: item.jam_buka,
        jam_tutup: item.jam_tutup,
      });

      marker.setStyle(
        new Style({
          image: new Icon({
            anchor: [0.5, 1],
            src: getIconForCategory(item.kategori),
            scale: 0.05,
          }),
        })
      );

      fasilitasLayer.getSource().addFeature(marker);

      const card = document.createElement("div");
      card.className =
        "bg-white shadow-md w-full rounded-lg overflow-hidden transition-transform transform hover:shadow-xl mb-4 cursor-pointer";
      card.innerHTML = `
        <img src="${item.foto}" class="w-full h-[170px] object-cover" alt="${item.nama
        }" />
        <div class="p-4">
          <h1 class="font-bold text-lg text-sky-900 mb-2">${item.nama}</h1>
          <p class="text-sm font-semibold text-gray-800">Jam Operasional: ${item.jam_buka
        } - ${item.jam_tutup}</p>
          <p class="text-sm text-gray-500 mt-2">Lokasi: ${item.kecamatan}</p>
        </div>
      `;

      card.addEventListener("click", () => {
        document.getElementById(
          "modal-image"
        ).src = `${item.foto}`;
        document.getElementById("modal-nama").innerText = item.nama;
        document.getElementById("modal-deskripsi").innerText = item.kategori;
        document.getElementById("modal-jamBuka").innerText =
          "Jam Buka: " + item.jam_buka;
        document.getElementById("modal-lokasi").innerText =
          "Lokasi: " + item.alamat;

        document.getElementById("modal").classList.remove("hidden");
      });

      fasilitasContainer.appendChild(card);
    });
  }
};


document.getElementById("kecamatan_options").addEventListener("change", (e) => {
  const selectedKecamatan = e.target.value;
  const selectedCategory = document
    .querySelector(".category_item.active")
    ?.getAttribute("data-category");

  filterByCategoryAndKecamatan(selectedCategory, selectedKecamatan);
});

const removeActiveClass = () => {
  const categories = document.querySelectorAll(".category_item");
  categories.forEach((category) => {
    category.classList.remove("active");
  });
};

document.getElementById("category_religion").addEventListener("click", () => {
  removeActiveClass();
  document.getElementById("category_religion").classList.add("active");
  const selectedKecamatan = document.getElementById("kecamatan_options").value;
  filterByCategoryAndKecamatan("Tempat Ibadah", selectedKecamatan);
});

document.getElementById("category_park").addEventListener("click", () => {
  removeActiveClass();
  document.getElementById("category_park").classList.add("active");
  const selectedKecamatan = document.getElementById("kecamatan_options").value;
  filterByCategoryAndKecamatan("Taman", selectedKecamatan);
});

document.getElementById("category_shopping").addEventListener("click", () => {
  removeActiveClass();
  document.getElementById("category_shopping").classList.add("active");
  const selectedKecamatan = document.getElementById("kecamatan_options").value;
  filterByCategoryAndKecamatan("Tempat Belanja", selectedKecamatan);
});

document.getElementById("category_transport").addEventListener("click", () => {
  removeActiveClass();
  document.getElementById("category_transport").classList.add("active");
  const selectedKecamatan = document.getElementById("kecamatan_options").value;
  filterByCategoryAndKecamatan("Halte Bus", selectedKecamatan);
});

document.getElementById("close-modal").addEventListener("click", () => {
  document.getElementById("modal").classList.add("hidden");
});

document.getElementById("modal").addEventListener("click", (e) => {
  if (e.target === document.getElementById("modal")) {
    document.getElementById("modal").classList.add("hidden");
  }
});

popupCloser.addEventListener("click", () => {
  popup.classList.add("hidden");
});

popup.addEventListener("click", (e) => {
  if (e.target === popup) {
    popup.classList.add("hidden");
  } 
});
