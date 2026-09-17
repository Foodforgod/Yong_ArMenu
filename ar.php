<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AR Menu - Italian Kitchen</title>
    <!-- A-Frame & MindAR -->
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
    <style>
        body { margin: 0; overflow: hidden; font-family: sans-serif; }
        #ar-ui { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; pointer-events: none; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box; padding: 20px; }
        .interactive { pointer-events: auto; }
        .top-nav { display: flex; justify-content: space-between; align-items: center; }
        .back-btn { background: rgba(0,0,0,0.6); color: white; border: none; padding: 10px 18px; border-radius: 20px; font-weight: bold; text-decoration: none; backdrop-filter: blur(5px); }
        .status-badge { background: rgba(178, 34, 34, 0.85); color: white; padding: 8px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; backdrop-filter: blur(5px); }
        
        /* Scrollable Dish Selector HUD */
        .dish-selector { background: rgba(0,0,0,0.75); backdrop-filter: blur(8px); padding: 12px; border-radius: 16px; display: flex; justify-content: flex-start; gap: 10px; margin-bottom: 10px; overflow-x: auto; scrollbar-width: none; }
        .dish-selector::-webkit-scrollbar { display: none; }
        .dish-btn { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 10px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; cursor: pointer; white-space: nowrap; transition: 0.2s; flex-shrink: 0; }
        .dish-btn.active, .dish-btn:hover { background: #ffeb3b; color: #111; border-color: #ffeb3b; }

        #loader-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 99; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; transition: opacity 0.5s; }
        .spinner { width: 50px; height: 50px; border: 5px solid #555; border-top: 5px solid #ffeb3b; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div id="loader-overlay">
        <div class="spinner"></div>
        <div style="font-weight: bold; font-size: 1.1rem;">Loading Gourmet 3D Menu...</div>
        <button onclick="dismissLoader()" style="margin-top: 20px; background: #b22222; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: bold;">Start AR Now</button>
    </div>

    <div id="ar-ui">
        <div class="top-nav interactive">
            <a href="index.php" class="back-btn">← Exit</a>
            <div id="status" class="status-badge">Searching for Menu Poster...</div>
        </div>

        <!-- Interactive Dish Selector Bar -->
        <div class="dish-selector interactive" id="dish-hud-bar">
            <!-- Dynamically populated from layout.json -->
        </div>
    </div>

    <!-- AR Scene tracking the full menu poster as Target 0 -->
    <a-scene mindar-image="imageTargetSrc: assets/targets/targets.mind; autoStart: true;" color-space="sRGB" renderer="colorManagement: true, physicallyCorrectLights: true" vr-mode-ui="enabled: false" device-orientation-permission-ui="enabled: true">
        <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

        <!-- Studio Lighting -->
        <a-entity light="type: ambient; color: #FFF; intensity: 1.5"></a-entity>
        <a-entity light="type: directional; color: #FFF; intensity: 1.2" position="1 3 2"></a-entity>

        <!-- Main Anchor for Whole Menu Poster -->
        <a-entity mindar-image-target="targetIndex: 0" id="main-menu-target">
            <!-- Dynamic 3D Models container injected via JS based on editor.php layout -->
            <a-entity id="models-container"></a-entity>
        </a-entity>
    </a-scene>

    <script>
        function dismissLoader() {
            const loader = document.getElementById('loader-overlay');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
            }
        }

        setTimeout(dismissLoader, 3000);

        let activeDishType = null;

        // Templates for 3D Models
       const modelTemplates = {
            salad: `
                <a-cylinder radius="0.75" height="0.05" color="#95a5a6" position="0 0.03 0" roughness="0.3"></a-cylinder>
                <a-torus radius="0.72" radius-tubular="0.02" color="#7f8c8d" position="0 0.06 0" rotation="90 0 0"></a-torus>
                <a-cylinder radius="0.25" height="0.05" color="#d32f2f" position="0.12 0.08 0.08" rotation="25 15 -10"></a-cylinder>
                <a-cylinder radius="0.25" height="0.05" color="#d32f2f" position="-0.12 0.08 -0.08" rotation="-20 30 15"></a-cylinder>
                <a-cylinder radius="0.23" height="0.05" color="#e53935" position="-0.12 0.09 0.12" rotation="20 -25 5"></a-cylinder>
                <a-cylinder radius="0.23" height="0.05" color="#e53935" position="0.12 0.09 -0.12" rotation="-15 -20 -10"></a-cylinder>
                <a-cylinder radius="0.2" height="0.07" color="#ffffff" position="0 0.12 0" rotation="10 45 0"></a-cylinder>
                <a-box width="0.15" height="0.015" depth="0.25" color="#2e7d32" position="0.05 0.15 0.1" rotation="10 50 15"></a-box>
            `,
            pasta: `
                <a-cylinder radius="0.75" height="0.05" color="#95a5a6" position="0 0.03 0"></a-cylinder>
                <a-torus radius="0.72" radius-tubular="0.02" color="#7f8c8d" position="0 0.06 0" rotation="90 0 0"></a-torus>
                <a-torus radius="0.38" radius-tubular="0.025" color="#9ccc65" position="0 0.10 0" rotation="80 15 0"></a-torus>
                <a-torus radius="0.34" radius-tubular="0.025" color="#8bc34a" position="0 0.13 0" rotation="95 -20 10"></a-torus>
                <a-torus radius="0.30" radius-tubular="0.025" color="#aed581" position="0 0.16 0" rotation="85 45 -10"></a-torus>
                <a-sphere radius="0.12" color="#558b2f" position="0 0.27 0" scale="1 0.3 1"></a-sphere>
            `,
            pizza: `
                <a-cylinder radius="0.55" height="0.02" color="#d4a373" position="0 0.02 0"></a-cylinder>
                <a-torus radius="0.54" radius-tubular="0.015" color="#bc6c25" position="0 0.03 0" rotation="90 0 0"></a-torus>
                <a-cylinder radius="0.52" height="0.022" color="#c0392b" position="0 0.025 0"></a-cylinder>
                <a-cylinder radius="0.14" height="0.025" color="#fffaf0" position="0.15 0.03 0.15" rotation="2 10 0"></a-cylinder>
                <a-cylinder radius="0.13" height="0.025" color="#fffaf0" position="-0.18 0.03 -0.12" rotation="-3 20 5"></a-cylinder>
                <a-cylinder radius="0.12" height="0.025" color="#fffaf0" position="-0.12 0.03 0.18" rotation="4 -15 0"></a-cylinder>
                <a-box width="0.09" height="0.005" depth="0.15" color="#2e7d32" position="0.05 0.045 0.12" rotation="0 35 4"></a-box>
            `,
       dessert: `
                <a-cylinder radius="0.35" height="0.38" color="#ffffff" opacity="0.3" transparent="true" position="0 0.2 0" roughness="0.1"></a-cylinder>
                <a-cylinder radius="0.32" height="0.25" color="#fffdfa" position="0 0.18 0"></a-cylinder>
                <a-cylinder radius="0.32" height="0.06" color="#ad1457" position="0 0.32 0"></a-cylinder>
                <a-sphere radius="0.08" color="#d81b60" position="0.05 0.38 0.05"></a-sphere>
            `,
            tiramisu: `
                <!-- Ceramic Rectangular/Square Dish Base -->
                <a-box width="0.75" height="0.06" depth="0.6" color="#ecf0f1" position="0 0.03 0" roughness="0.3"></a-box>
                <!-- Ladyfinger & Mascarpone Layer 1 -->
                <a-box width="0.68" height="0.12" depth="0.54" color="#d7ccc8" position="0 0.12 0"></a-box>
                <!-- Creamy Mascarpone Layer 2 -->
                <a-box width="0.66" height="0.14" depth="0.52" color="#fff8e1" position="0 0.25 0"></a-box>
                <!-- Cocoa Powder Dusting Top Layer -->
                <a-box width="0.65" height="0.03" depth="0.51" color="#4e342e" position="0 0.33 0" roughness="0.9"></a-box>
                <!-- Chocolate Shavings / Coffee Bean Garnish Accent -->
                <a-cylinder radius="0.04" height="0.02" color="#271c19" position="0.15 0.35 0.1" rotation="10 15 0"></a-cylinder>
                <a-cylinder radius="0.035" height="0.02" color="#271c19" position="-0.12 0.35 -0.1" rotation="-5 30 10"></a-cylinder>
                <a-box width="0.12" height="0.015" depth="0.04" color="#2e7d32" position="0 0.36 -0.15" rotation="0 20 0"></a-box>
            `
        };

        async function loadLayoutAndBuildAR() {
            let layoutData = {
                dishes: [
                    { id: "dish-1", type: "salad", name: "Caprese Salad", nx: 0.5, ny: 0.22 },
                    { id: "dish-2", type: "pasta", name: "Spaghetti al Pesto", nx: 0.5, ny: 0.38 },
                    { id: "dish-3", type: "pizza", name: "Pizza Margherita", nx: 0.5, ny: 0.55 },
                    { id: "dish-4", type: "dessert", name: "Berry Panna Cotta", nx: 0.5, ny: 0.72 },
                    { id: "dish-5", type: "tiramisu", name: "Tiramisù", nx: 0.5, ny: 0.88 }
                ]
            };

            try {
                const res = await fetch('assets/targets/layout.json?t=' + new Date().getTime());
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.dishes) layoutData = data;
                }
            } catch (e) {
                console.warn("Using fallback layout mapping.");
            }

            const hudBar = document.getElementById('dish-hud-bar');
            const modelsContainer = document.getElementById('models-container');
            hudBar.innerHTML = '';
            modelsContainer.innerHTML = '';

            layoutData.dishes.forEach((dish, idx) => {
                // Convert normalized 0-1 editor coordinates (nx, ny) to A-Frame AR relative plane coordinates
                // MindAR plane is roughly 1 unit wide, centered at 0,0. Adjust scaling factors as needed.
                const posX = (dish.nx - 0.5) * 1.0; 
                const posY = (0.5 - dish.ny) * 1.4; 

                // Build HUD button
                const btn = document.createElement('button');
                btn.className = `dish-btn ${idx === 2 ? 'active' : ''}`; // Default to pizza or first item
                btn.innerHTML = getEmoji(dish.type) + " " + dish.name;
                btn.onclick = () => selectDish(dish.type, btn);
                hudBar.appendChild(btn);

                // Build AR Entity positioned exactly where saved in editor.php
                const entityHtml = `
                    <a-entity class="ar-dish-model" data-type="${dish.type}" position="${posX.toFixed(3)} ${posY.toFixed(3)} 0" scale="0.15 0.15 0.15" visible="${idx === 2 ? 'true' : 'false'}" animation="property: rotation; to: 0 360 0; loop: true; dur: 16000; easing: linear">
                        <a-entity>
                            ${modelTemplates[dish.type] || modelTemplates['pizza']}
                        </a-entity>
                    </a-entity>
                `;
                modelsContainer.insertAdjacentHTML('beforeend', entityHtml);
                
                if (idx === 2) activeDishType = dish.type;
            });
        }

        function getEmoji(type) {
            const emojis = { salad: '🥗', pasta: '🍝', pizza: '🍕', dessert: '🍓', tiramisu: '🧋' };
            return emojis[type] || '🍽️';
        }

        function selectDish(type, btnElement) {
            document.querySelectorAll('.dish-btn').forEach(b => b.classList.remove('active'));
            if(btnElement) {
                btnElement.classList.add('active');
                btnElement.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
            activeDishType = type;
            document.querySelectorAll('.ar-dish-model').forEach(el => {
                el.setAttribute('visible', el.getAttribute('data-type') === type ? 'true' : 'false');
            });
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await loadLayoutAndBuildAR();

            const statusEl = document.getElementById('status');
            const menuTarget = document.querySelector('#main-menu-target');

            if (menuTarget) {
                menuTarget.addEventListener('targetFound', () => {
                    statusEl.innerText = "Menu Poster Tracked!";
                    statusEl.style.background = "rgba(46, 139, 87, 0.85)";
                    dismissLoader();
                });

                menuTarget.addEventListener('targetLost', () => {
                    statusEl.innerText = "Searching for Menu Poster...";
                    statusEl.style.background = "rgba(178, 34, 34, 0.85)";
                });
            }
        });
    </script>
</body>
</html>