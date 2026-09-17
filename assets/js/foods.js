/**
 * Ultra-Realistic Procedural 3D Food Models Registry using Three.js
 */
const FoodRegistry = {
    // 1. Caprese Salad: Ceramic plate with overlapping vibrant red tomatoes, fresh buffalo mozzarella, and glossy basil leaves
    createSalad() {
        const group = new THREE.Group();
        
        // Ceramic Plate with subtle rim
        const plateGeo = new THREE.CylinderGeometry(0.95, 0.75, 0.07, 32);
        const plateMat = new THREE.MeshStandardMaterial({ color: 0xfcfcfc, roughness: 0.15, metalness: 0.05 });
        const plate = new THREE.Mesh(plateGeo, plateMat);
        group.add(plate);

        // Plate rim inner depression
        const innerPlateGeo = new THREE.CylinderGeometry(0.75, 0.75, 0.02, 32);
        const innerPlateMat = new THREE.MeshStandardMaterial({ color: 0xf4f4f4, roughness: 0.2 });
        const innerPlate = new THREE.Mesh(innerPlateGeo, innerPlateMat);
        innerPlate.position.y = 0.04;
        group.add(innerPlate);

        // Alternating Slices of Tomato and Mozzarella arranged in an overlapping ring
        const count = 8;
        for (let i = 0; i < count; i++) {
            const angle = (i / count) * Math.PI * 2;
            const radius = 0.42;
            const x = Math.cos(angle) * radius;
            const z = Math.sin(angle) * radius;

            const isTomato = i % 2 === 0;
            const sliceGeo = new THREE.CylinderGeometry(0.24, 0.24, 0.07, 24);
            const sliceMat = new THREE.MeshStandardMaterial({ 
                color: isTomato ? 0xe62739 : 0xfffff0, 
                roughness: isTomato ? 0.25 : 0.4,
                metalness: 0.05
            });
            const slice = new THREE.Mesh(sliceGeo, sliceMat);
            slice.position.set(x, 0.08, z);
            // Tilt them slightly for realistic overlap
            slice.rotation.z = isTomato ? 0.25 : -0.25;
            slice.rotation.x = isTomato ? -0.15 : 0.15;
            slice.rotation.y = angle;
            group.add(slice);
        }

        // Center Fresh Basil Garnish & Olive Oil Drizzle look
        for (let b = 0; b < 4; b++) {
            const leafGeo = new THREE.BoxGeometry(0.18, 0.01, 0.28);
            const leafMat = new THREE.MeshStandardMaterial({ color: 0x1e824c, roughness: 0.3 });
            const leaf = new THREE.Mesh(leafGeo, leafMat);
            const bAngle = (b / 4) * Math.PI * 2;
            leaf.position.set(Math.cos(bAngle) * 0.15, 0.14, Math.sin(bAngle) * 0.15);
            leaf.rotation.y = bAngle;
            leaf.rotation.z = 0.2;
            group.add(leaf);
        }

        return group;
    },

    // 2. Spaghetti al Pesto: Deep ceramic bowl with rich textured green pesto noodles and garnish
    createPasta() {
        const group = new THREE.Group();
        
        // Deep Bowl
        const bowlGeo = new THREE.CylinderGeometry(0.85, 0.45, 0.38, 32);
        const bowlMat = new THREE.MeshStandardMaterial({ color: 0xf8f8f8, roughness: 0.2 });
        const bowl = new THREE.Mesh(bowlGeo, bowlMat);
        bowl.position.y = 0.15;
        group.add(bowl);

        // Inner Bowl Shadow/Depth
        const innerGeo = new THREE.CylinderGeometry(0.78, 0.4, 0.05, 32);
        const innerMat = new THREE.MeshStandardMaterial({ color: 0xe0e0e0, roughness: 0.5 });
        const inner = new THREE.Mesh(innerGeo, innerMat);
        inner.position.y = 0.3;
        group.add(inner);

        // Pesto Noodles (Detailed Torus Knots for rich organic pasta coil appearance)
        const noodleGeo = new THREE.TorusKnotGeometry(0.48, 0.12, 64, 32, 2, 5);
        const noodleMat = new THREE.MeshStandardMaterial({ color: 0x4a7c23, roughness: 0.4, metalness: 0.1 });
        const noodles = new THREE.Mesh(noodleGeo, noodleMat);
        noodles.position.y = 0.28;
        noodles.rotation.x = Math.PI / 2;
        group.add(noodles);

        // Top Fresh Basil Leaves & Parmigiano Dusting dots
        for (let i = 0; i < 3; i++) {
            const leafGeo = new THREE.BoxGeometry(0.15, 0.01, 0.22);
            const leafMat = new THREE.MeshStandardMaterial({ color: 0x27ae60, roughness: 0.3 });
            const leaf = new THREE.Mesh(leafGeo, leafMat);
            leaf.position.set((i - 1) * 0.15, 0.45, (i % 2) * 0.1);
            leaf.rotation.y = i * 1.2;
            group.add(leaf);
        }

        return group;
    },

    // 3. Pizza Margherita: Golden wood-fired crust, rich red sauce, melted mozzarella pools, and fresh basil
    createPizza() {
        const group = new THREE.Group();
        
        // Golden Brown Crust (Outer rim)
        const crustGeo = new THREE.CylinderGeometry(0.95, 0.92, 0.12, 32);
        const crustMat = new THREE.MeshStandardMaterial({ color: 0xd4a373, roughness: 0.7 });
        const crust = new THREE.Mesh(crustGeo, crustMat);
        crust.position.y = 0.05;
        group.add(crust);

        // Tomato Sauce & Melted Cheese Base
        const sauceGeo = new THREE.CylinderGeometry(0.82, 0.82, 0.13, 32);
        const sauceMat = new THREE.MeshStandardMaterial({ color: 0xf1c40f, roughness: 0.25 });
        const sauce = new THREE.Mesh(sauceGeo, sauceMat);
        sauce.position.y = 0.07;
        group.add(sauce);

        // Melted Mozzarella Blobs & Fresh Basil leaves
        for (let i = 0; i < 6; i++) {
            const angle = (i / 6) * Math.PI * 2;
            
            // Mozzarella Dollop
            const dollopGeo = new THREE.SphereGeometry(0.18, 16, 16);
            dollopGeo.scale(1, 0.35, 1);
            const dollopMat = new THREE.MeshStandardMaterial({ color: 0xfffaf0, roughness: 0.4 });
            const dollop = new THREE.Mesh(dollopGeo, dollopMat);
            dollop.position.set(Math.cos(angle) * 0.5, 0.14, Math.sin(angle) * 0.5);
            group.add(dollop);

            // Basil Leaf
            const basilGeo = new THREE.BoxGeometry(0.14, 0.01, 0.22);
            const basilMat = new THREE.MeshStandardMaterial({ color: 0x27ae60, roughness: 0.3 });
            const basil = new THREE.Mesh(basilGeo, basilMat);
            basil.position.set(Math.cos(angle + 0.5) * 0.4, 0.15, Math.sin(angle + 0.5) * 0.4);
            basil.rotation.y = angle;
            group.add(basil);
        }

        return group;
    },

    // 4. Berry Panna Cotta: Clear dessert glass with creamy vanilla body, red berry coulis, and fresh strawberry top
    createPannaCotta() {
        const group = new THREE.Group();
        
        // Elegant Glass Cup (Transparent Glass Material)
        const glassGeo = new THREE.CylinderGeometry(0.48, 0.32, 0.85, 32);
        const glassMat = new THREE.MeshPhysicalMaterial({ 
            color: 0xffffff, 
            transparent: true, 
            opacity: 0.35, 
            roughness: 0.05, 
            transmission: 0.95, 
            ior: 1.5 
        });
        const glass = new THREE.Mesh(glassGeo, glassMat);
        glass.position.y = 0.42;
        group.add(glass);

        // Cream Body
        const creamGeo = new THREE.CylinderGeometry(0.45, 0.3, 0.65, 32);
        const creamMat = new THREE.MeshStandardMaterial({ color: 0xfdfbf7, roughness: 0.35 });
        const cream = new THREE.Mesh(creamGeo, creamMat);
        cream.position.y = 0.38;
        group.add(cream);

        // Rich Berry Coulis Top Layer (Glossy Red)
        const coulisGeo = new THREE.CylinderGeometry(0.46, 0.44, 0.12, 32);
        const coulisMat = new THREE.MeshStandardMaterial({ color: 0xc0392b, roughness: 0.15, metalness: 0.1 });
        const coulis = new THREE.Mesh(coulisGeo, coulisMat);
        coulis.position.y = 0.68;
        group.add(coulis);

        // Fresh Strawberry Garnish on top
        const berryGeo = new THREE.ConeGeometry(0.14, 0.25, 16);
        const berryMat = new THREE.MeshStandardMaterial({ color: 0xe74c3c, roughness: 0.25 });
        const berry = new THREE.Mesh(berryGeo, berryMat);
        berry.position.set(0, 0.82, 0);
        group.add(berry);

        return group;
    },

    // 5. Tiramisu: Glass cup with structured mascarpone cream layers, sponge cake, and dusting cocoa powder
    createTiramisu() {
        const group = new THREE.Group();
        
        // Glass Cup
        const glassGeo = new THREE.CylinderGeometry(0.48, 0.32, 0.85, 32);
        const glassMat = new THREE.MeshPhysicalMaterial({ 
            color: 0xffffff, 
            transparent: true, 
            opacity: 0.35, 
            roughness: 0.05, 
            transmission: 0.95, 
            ior: 1.5 
        });
        const glass = new THREE.Mesh(glassGeo, glassMat);
        glass.position.y = 0.42;
        group.add(glass);

        // Layered Cream and Sponge Cake Structure
        const layersData = [
            { color: 0xf5e6ca, height: 0.2 }, // Mascarpone Cream
            { color: 0x8d5524, height: 0.2 }, // Espresso Ladyfingers
            { color: 0xf5e6ca, height: 0.2 }  // Mascarpone Cream
        ];

        layersData.forEach((data, index) => {
            const layerGeo = new THREE.CylinderGeometry(0.45 - (index * 0.02), 0.38 - (index * 0.02), data.height, 32);
            const layerMat = new THREE.MeshStandardMaterial({ color: data.color, roughness: 0.5 });
            const layer = new THREE.Mesh(layerGeo, layerMat);
            layer.position.y = 0.22 + (index * 0.2);
            group.add(layer);
        });

        // Rich Cocoa Powder Top Dusting
        const cocoaGeo = new THREE.CylinderGeometry(0.43, 0.43, 0.04, 32);
        const cocoaMat = new THREE.MeshStandardMaterial({ color: 0x4a3525, roughness: 0.85 });
        const cocoa = new THREE.Mesh(cocoaGeo, cocoaMat);
        cocoa.position.y = 0.76;
        group.add(cocoa);

        return group;
    },

    // Central registry dispatcher matching the exact menu item keys
    getModel(type) {
        let model;
        switch (type) {
            case 'salad': model = this.createSalad(); break;
            case 'pasta': model = this.createPasta(); break;
            case 'pizza': model = this.createPizza(); break;
            case 'dessert': model = this.createPannaCotta(); break;
            case 'tiramisu': model = this.createTiramisu(); break;
            default: model = this.createPannaCotta(); break;
        }
        model.scale.set(0.65, 0.65, 0.65);
        return model;
    }
};