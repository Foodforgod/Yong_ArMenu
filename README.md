# 🍽️ AR Interactive Menu & Food Manager

An Augmented Reality (AR) web application built for restaurants, allowing customers to scan a physical menu poster and instantly view mouth-watering 3D food models (Salad, Pasta, Pizza, Panna Cotta, and Tiramisu) directly over their table. It features a built-in visual **Layout & Marker Manager** for precise coordinate mapping.

---

## ✨ Key Features

* **Markerless-Style Experience via Single-Target Tracking:** Tracks the entire menu poster as a single image target (`targets.mind`) to prevent tracking loss.
* **Dynamic 3D Positioning:** Reads spatial coordinates dynamically from a JSON layout configuration, eliminating hardcoded placement errors.
* **Visual Editor & AI Auto-Align (`editor.php`):** Easily upload new menu posters, click to position items, or use the built-in AI assistant to evenly distribute markers.
* **Interactive HUD Menu Selector:** Switch between dishes instantly using an on-screen responsive scrollable bar.
* **Custom 3D Procedural Models:** Lightweight, clean A-Frame geometric models representing various dishes with realistic plating effects (glass containers, toppings, textures).

---

## 🛠️ Tech Stack

* **Front-End AR:** [A-Frame](https://aframe.io/) (v1.4.0) & [MindAR](https://mindar.world/) (v1.2.5)
* **Back-End Logic:** PHP (for file handling and JSON layout configuration storage)
* **Styling:** Responsive custom CSS with smooth UI transitions

---

## 📁 Project Structure

├── assets/
│   └── targets/
│       ├── targets.mind      # Compiled MindAR image target file
│       └── layout.json       # Dynamic 3D model coordinates configuration
├── index.php                 # Dashboard home / entry point
├── editor.php                # Visual marker manager & AI layout alignment tool
├── ar.php                    # Main AR viewer experience rendering 3D models
├── menu_2.jpg                # Current active menu poster image
└── README.md                 # Project documentation
🚀 Getting Started & Installation
Clone or Download the Repository into your local server environment (e.g., XAMPP, WAMP, or a live PHP-enabled hosting server).

Ensure PHP Write Permissions: Make sure the assets/targets/ directory has write permissions so the layout editor can successfully save changes to layout.json.

Run the Project:

Open index.php in your browser to access the dashboard.

Use editor.php to upload your own menu poster or fine-tune dish coordinates.

Open ar.php on your mobile phone or AR-compatible device to scan the menu poster.

📱 How to Use
<img width="1906" height="987" alt="image" src="https://github.com/user-attachments/assets/d8468a94-65da-4b38-8b91-7758a8280033" />
<img width="1887" height="980" alt="image" src="https://github.com/user-attachments/assets/ff9f95d4-b1d4-48af-a2dc-e40ccb4398e5" />
Calibrate Layout: Go to editor.php, upload your menu poster (menu_2.jpg), and align the markers over your menu items. Click Save Layout Changes.

Launch AR: Open ar.php on your smartphone and grant camera permissions.

Scan Poster: Point your camera at the printed menu poster. Once tracked, the default 3D model will appear.

Interact: Use the bottom dish selector bar to switch between different food items dynamically.

📄 License
This project is open-source and available for commercial or personal restaurant use.
