<?php
// Handle image upload and layout saving
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonInput = file_get_contents('php://input');
    if ($jsonInput && !empty(json_decode($jsonInput))) {
        if (!file_exists('assets/targets')) {
            mkdir('assets/targets', 0777, true);
        }
        file_put_contents('assets/targets/layout.json', $jsonInput);
        echo json_encode(["status" => "success", "message" => "Layout updated successfully"]);
        exit;
    }

    if (isset($_FILES['menu_image'])) {
        $targetFile = "menu_2.jpg";
        $check = getimagesize($_FILES["menu_image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["menu_image"]["tmp_name"], $targetFile)) {
                header("Location: editor.php?uploaded=true");
                exit;
            }
        }
        header("Location: editor.php?error=upload_failed");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced AR Menu Editor - Italian Kitchen</title>
    <style>
        body { font-family: sans-serif; background: #1e1e1e; color: #fff; margin: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .header-wrap { max-width: 1100px; width: 100%; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        h1 { margin: 0 0 5px 0; color: #ffeb3b; font-size: 1.5rem; }
        p { color: #aaa; margin: 0; font-size: 0.9rem; }
        .workspace { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; max-width: 1100px; width: 100%; }
        
        .canvas-container { position: relative; border: 3px solid #444; border-radius: 8px; overflow: hidden; background: #000; width: 360px; height: auto; max-height: 600px; cursor: crosshair; display: flex; align-items: center; justify-content: center; }
        .canvas-container img { width: 100%; height: auto; display: block; object-fit: contain; }
        
        .marker-pin { position: absolute; width: 26px; height: 26px; background: #b22222; border: 2px solid white; border-radius: 50%; transform: translate(-50%, -50%); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; box-shadow: 0 2px 6px rgba(0,0,0,0.6); pointer-events: none; z-index: 5; }
        
        .controls-panel { background: #2d2d2d; padding: 20px; border-radius: 8px; flex: 1; min-width: 320px; max-width: 600px; box-sizing: border-box; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .upload-box, .action-box { background: #383838; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px dashed #666; }
        .upload-box h3, .action-box h3 { margin: 0 0 8px 0; color: #ffeb3b; font-size: 0.95rem; }
        
        .dish-row { background: #383838; padding: 12px; border-radius: 6px; margin-bottom: 12px; position: relative; }
        .dish-row h4 { margin: 0 0 8px 0; color: #ffeb3b; font-size: 1rem; display: flex; justify-content: space-between; align-items: center; }
        label { font-size: 0.8rem; display: block; color: #bbb; margin-bottom: 3px; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 6px; box-sizing: border-box; background: #1a1a1a; border: 1px solid #555; color: white; border-radius: 4px; margin-bottom: 6px; font-size: 0.9rem; }
        
        .btn { background: #2e8b57; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; font-size: 1rem; margin-top: 5px; box-shadow: 0 2px 6px rgba(46,139,87,0.3); }
        .btn:hover { background: #226b43; }
        .btn-upload { background: #007acc; }
        .btn-upload:hover { background: #005999; }
        .btn-ai { background: #8a2be2; }
        .btn-ai:hover { background: #6a1b9a; }
        .btn-danger { background: #b22222; padding: 4px 8px; font-size: 0.75rem; width: auto; margin: 0; }
        .btn-danger:hover { background: #8b0000; }
        .btn-add { background: #ff8c00; margin-bottom: 15px; }
        .btn-add:hover { background: #e07b00; }

        .back-link { color: #aaa; text-decoration: none; font-size: 0.9rem; display: block; margin-bottom: 5px; }
        .back-link:hover { color: white; }
        .alert { background: #2e8b57; color: white; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="header-wrap">
        <div>
            <a href="index.php" class="back-link">← Back to Dashboard</a>
            <h1>AR Menu, Food & Marker Manager</h1>
            <p>Add new dishes, edit positions, or let AI auto-pull and align markers correctly.</p>
        </div>
    </div>

    <?php if (isset($_GET['uploaded'])): ?>
        <div style="max-width: 1100px; width: 100%;">
            <div class="alert">✅ Menu poster updated successfully!</div>
        </div>
    <?php endif; ?>

    <div class="workspace">
        <!-- Poster Preview & Upload Container -->
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div class="canvas-container" id="preview-box">
                <img id="menu-poster" src="menu_2.jpg?t=<?php echo time(); ?>" alt="Menu Poster">
                <div id="pins-overlay"></div>
            </div>

            <div class="controls-panel" style="padding: 15px;">
                <div class="upload-box" style="margin:0;">
                    <h3>Upload New Menu Poster</h3>
                    <form action="editor.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="menu_image" accept="image/*" required style="color:#ccc; font-size:0.85rem; width:100%; margin-bottom:8px;">
                        <button type="submit" class="btn btn-upload">📤 Upload & Replace Poster</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Controls Panel -->
        <div class="controls-panel">
            <h3>Menu Items & 3D Coordinates</h3>
            
            <div class="action-box">
                <h3>🤖 AI Smart Assistant</h3>
                <p style="font-size:0.8rem; color:#bbb; margin-bottom:8px;">Automatically distribute all dish markers evenly down the vertical axis of your menu layout.</p>
                <button class="btn btn-ai" onclick="aiAutoAlignMarkers()">✨ AI Auto-Align & Pull Markers</button>
            </div>

            <button class="btn btn-add" onclick="addNewDish()">➕ Add New Food Item</button>
            
            <div id="dish-list-editor">Auto-pulling layout configuration...</div>
            <button class="btn" onclick="saveLayout()">💾 Save Layout Changes</button>
        </div>
    </div>

    <script>
        let layoutData = {
            version: 1,
            dishes: [
                { id: "dish-1", type: "salad", name: "Caprese Salad", nx: 0.5, ny: 0.22 },
                { id: "dish-2", type: "pasta", name: "Spaghetti al Pesto", nx: 0.5, ny: 0.38 },
                { id: "dish-3", type: "pizza", name: "Pizza Margherita", nx: 0.5, ny: 0.55 },
                { id: "dish-4", type: "dessert", name: "Berry Panna Cotta", nx: 0.5, ny: 0.72 },
                { id: "dish-5", type: "tiramisu", name: "Tiramisù", nx: 0.5, ny: 0.88 }
            ]
        };
        let selectedDishIdx = 0;

        async function autoPullLayout() {
            try {
                const res = await fetch('assets/targets/layout.json?t=' + new Date().getTime());
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.dishes) {
                        layoutData = data;
                    }
                }
            } catch (e) {
                console.warn("Using default layout configuration fallback.");
            }
            renderEditor();
        }

        function renderEditor() {
            const editorContainer = document.getElementById('dish-list-editor');
            const pinsContainer = document.getElementById('pins-overlay');
            editorContainer.innerHTML = '';
            pinsContainer.innerHTML = '';

            layoutData.dishes.forEach((dish, idx) => {
                const row = document.createElement('div');
                row.className = 'dish-row';
                row.style.borderLeft = selectedDishIdx === idx ? '4px solid #ffeb3b' : '4px solid transparent';
                row.innerHTML = `
                    <h4 onclick="selectedDishIdx = ${idx}; renderEditor();" style="cursor:pointer;">
                        <span>${idx + 1}. ${dish.name} ${selectedDishIdx === idx ? '⭐ (Selected)' : ''}</span>
                        <button class="btn btn-danger" onclick="event.stopPropagation(); removeDish(${idx});">Delete</button>
                    </h4>
                    <label>Display Name:</label>
                    <input type="text" value="${dish.name}" oninput="layoutData.dishes[${idx}].name = this.value">
                    <label>3D Model Type:</label>
                    <select onchange="layoutData.dishes[${idx}].type = this.value">
                        <option value="salad" ${dish.type==='salad'?'selected':''}>Salad (Caprese)</option>
                        <option value="pasta" ${dish.type==='pasta'?'selected':''}>Pasta (Pesto)</option>
                        <option value="pizza" ${dish.type==='pizza'?'selected':''}>Pizza (Margherita)</option>
                        <option value="dessert" ${dish.type==='dessert'?'selected':''}>Dessert (Panna Cotta)</option>
                        <option value="tiramisu" ${dish.type==='tiramisu'?'selected':''}>Tiramisù</option>
                    </select>
                    <div style="display:flex; gap:10px; margin-top:5px;">
                        <div><label>NX (0-1):</label><input type="number" step="0.01" value="${dish.nx}" oninput="layoutData.dishes[${idx}].nx = parseFloat(this.value); renderPinsOnly();"></div>
                        <div><label>NY (0-1):</label><input type="number" step="0.01" value="${dish.ny}" oninput="layoutData.dishes[${idx}].ny = parseFloat(this.value); renderPinsOnly();"></div>
                    </div>
                `;
                editorContainer.appendChild(row);

                const pin = document.createElement('div');
                pin.className = 'marker-pin';
                pin.style.left = (dish.nx * 100) + '%';
                pin.style.top = (dish.ny * 100) + '%';
                pin.style.background = selectedDishIdx === idx ? '#ffeb3b' : '#b22222';
                pin.style.color = selectedDishIdx === idx ? '#000' : '#fff';
                pin.innerText = idx + 1;
                pinsContainer.appendChild(pin);
            });
        }

        function renderPinsOnly() {
            const pinsContainer = document.getElementById('pins-overlay');
            pinsContainer.innerHTML = '';
            layoutData.dishes.forEach((dish, idx) => {
                const pin = document.createElement('div');
                pin.className = 'marker-pin';
                pin.style.left = (dish.nx * 100) + '%';
                pin.style.top = (dish.ny * 100) + '%';
                pin.style.background = selectedDishIdx === idx ? '#ffeb3b' : '#b22222';
                pin.style.color = selectedDishIdx === idx ? '#000' : '#fff';
                pin.innerText = idx + 1;
                pinsContainer.appendChild(pin);
            });
        }

        function addNewDish() {
            const newId = "dish-" + (layoutData.dishes.length + 1);
            layoutData.dishes.push({
                id: newId,
                type: "salad",
                name: "New Menu Item",
                nx: 0.5,
                ny: 0.5
            });
            selectedDishIdx = layoutData.dishes.length - 1;
            renderEditor();
        }

        function removeDish(idx) {
            if (layoutData.dishes.length <= 1) {
                alert("You must keep at least one dish.");
                return;
            }
            layoutData.dishes.splice(idx, 1);
            selectedDishIdx = Math.max(0, idx - 1);
            renderEditor();
        }

        // AI Auto-Align / Pull Markers evenly across vertical menu layout
        function aiAutoAlignMarkers() {
            const total = layoutData.dishes.length;
            if (total === 0) return;
            
            // Distribute evenly between 15% and 88% vertical height
            const startY = 0.15;
            const endY = 0.88;
            const step = total > 1 ? (endY - startY) / (total - 1) : 0;

            layoutData.dishes.forEach((dish, idx) => {
                dish.nx = 0.5; // Center horizontally
                dish.ny = parseFloat((startY + (idx * step)).toFixed(3));
            });

            renderEditor();
            alert("🤖 AI successfully auto-pulled and aligned all " + total + " markers correctly!");
        }

        document.getElementById('menu-poster').addEventListener('click', (e) => {
            const rect = e.target.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;

            if (layoutData && layoutData.dishes[selectedDishIdx]) {
                layoutData.dishes[selectedDishIdx].nx = parseFloat(Math.max(0, Math.min(1, x)).toFixed(3));
                layoutData.dishes[selectedDishIdx].ny = parseFloat(Math.max(0, Math.min(1, y)).toFixed(3));
                renderEditor();
            }
        });

        async function saveLayout() {
            const res = await fetch('editor.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(layoutData, null, 2)
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert('Layout configuration saved successfully!');
            } else {
                alert('Error saving layout.');
            }
        }

        autoPullLayout();
    </script>
</body>
</html>