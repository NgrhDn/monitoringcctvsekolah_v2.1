<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard CCTV Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background-color: #f8f9f7;
    }
    .header {
      background-color: #9BB47C;
      padding: 15px;
      text-align: center;
      color: white;
    }
    .header h1 {
      margin: 5px 0;
    }
    .header small {
      font-size: 14px;
      display: block;
      margin-top: -5px;
    }
    .stats {
      display: flex;
      justify-content: center;
      gap: 10px;
      padding: 15px;
    }
    .stats div {
      background-color: #A6BF7B;
      padding: 10px 20px;
      border-radius: 8px;
      color: white;
      font-weight: bold;
    }
    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 20px;
    }
    .cctv-card {
      background-color: white;
      border-radius: 12px;
      padding: 15px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      cursor: pointer;
    }
    .cctv-card:hover {
      background-color: #eef5e9;
    }
    .cctv-card h3 {
      margin: 0;
      font-size: 16px;
      color: #2c3e50;
    }
    .cctv-card p {
      margin: 5px 0 0;
      font-weight: bold;
      font-size: 14px;
      color: #34495e;
    }
    iframe {
      width: 100%;
      height: 600px;
      border: none;
      margin-top: 10px;
      display: none;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>DASHBOARD CCTV SEKOLAH </h1>
    <small>----- Memantau Kondisi Sekolah DIY -----</small>
  </div>

  <div class="stats">
    <div id="totalCctv">Jumlah CCTV : -</div>
    <div id="totalSekolah">Jumlah Sekolah : -</div>
    <div id="totalWilayah">Jumlah Wilayah : -</div>
  </div>

  <div class="grid-container" id="cctvGrid"></div>

  <iframe id="cctvViewer"></iframe>

  <script>
    fetch("http://127.0.0.1:8000/api/cctvsekolah")
      .then(response => response.json())
      .then(data => {
        if (!data.success || !Array.isArray(data.data)) throw new Error("Data tidak valid");

        const grid = document.getElementById("cctvGrid");
        const wilayahSet = new Set();
        const sekolahSet = new Set();

        data.data.forEach(item => {
          wilayahSet.add(item.namaWilayah);
          sekolahSet.add(item.namaSekolah);

          const card = document.createElement("div");
          card.className = "cctv-card";
          card.innerHTML = `
            <h3>${item.namaSekolah}</h3>
            <p>${item.namaTitik}</p>
          `;
          card.addEventListener("click", () => {
            const viewer = document.getElementById("cctvViewer");
            viewer.src = item.link.replace(/^ttp/, 'http');
            viewer.style.display = "block";
            viewer.scrollIntoView({ behavior: "smooth" });
          });
          grid.appendChild(card);
        });

        // Statistik
        document.getElementById("totalCctv").textContent = `Jumlah CCTV : ${data.data.length}`;
        document.getElementById("totalSekolah").textContent = `Jumlah Sekolah : ${sekolahSet.size}`;
        document.getElementById("totalWilayah").textContent = `Jumlah Wilayah : ${wilayahSet.size}`;
      })
      .catch(error => {
        console.error("Gagal memuat data:", error);
        document.getElementById("cctvGrid").innerHTML = "<p>Gagal memuat CCTV.</p>";
      });
  </script>

</body>
</html>
