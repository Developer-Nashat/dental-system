<template>
    <div class="dental-chart">
        <!-- Upper Teeth -->
        <div class="teeth-row">
            <svg viewBox="0 0 1600 400" class="teeth-svg">
                <!-- Upper Right -->
                <g v-for="n in 8" :key="`upper-right-${n}`" 
                   :transform="`translate(${(n-1) * 100}, 0)`"
                   @click="$emit('select', n)"
                   :class="{ selected: modelValue === n }"
                   class="tooth">
                    <path :d="toothPaths[getToothType(n)].crown" class="tooth-crown" />
                    <path :d="toothPaths[getToothType(n)].root" class="tooth-root" />
                    <path :d="toothPaths[getToothType(n)].details" class="tooth-detail" />
                    <text x="50" y="170" class="tooth-number">{{ n }}</text>
                </g>

                <!-- Upper Left -->
                <g v-for="n in 8" :key="`upper-left-${n}`" 
                   :transform="`translate(${(n+7) * 100}, 0)`"
                   @click="$emit('select', n+8)"
                   :class="{ selected: modelValue === n+8 }"
                   class="tooth">
                    <path :d="toothPaths[getToothType(n+8)].crown" class="tooth-crown" />
                    <path :d="toothPaths[getToothType(n+8)].root" class="tooth-root" />
                    <path :d="toothPaths[getToothType(n+8)].details" class="tooth-detail" />
                    <text x="50" y="170" class="tooth-number">{{ n+8 }}</text>
                </g>
            </svg>
        </div>

        <!-- Lower Teeth -->
        <div class="teeth-row">
            <svg viewBox="0 0 1600 400" class="teeth-svg">
                <!-- Lower Right -->
                <g v-for="n in 8" :key="`lower-right-${n}`" 
                   :transform="`translate(${(n-1) * 100}, 200)`"
                   @click="$emit('select', n+16)"
                   :class="{ selected: modelValue === n+16 }"
                   class="tooth">
                    <path :d="toothPaths[getToothType(n+16)].crown" class="tooth-crown" />
                    <path :d="toothPaths[getToothType(n+16)].root" class="tooth-root" />
                    <path :d="toothPaths[getToothType(n+16)].details" class="tooth-detail" />
                    <text x="50" y="170" class="tooth-number">{{ n+16 }}</text>
                </g>

                <!-- Lower Left -->
                <g v-for="n in 8" :key="`lower-left-${n}`" 
                   :transform="`translate(${(n+7) * 100}, 200)`"
                   @click="$emit('select', n+24)"
                   :class="{ selected: modelValue === n+24 }"
                   class="tooth">
                    <path :d="toothPaths[getToothType(n+24)].crown" class="tooth-crown" />
                    <path :d="toothPaths[getToothType(n+24)].root" class="tooth-root" />
                    <path :d="toothPaths[getToothType(n+24)].details" class="tooth-detail" />
                    <text x="50" y="170" class="tooth-number">{{ n+24 }}</text>
                </g>
            </svg>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    modelValue: {
        type: Number,
        required: true
    }
});

defineEmits(['select']);

// Tooth shape paths based on tooth type
const toothPaths = {
    molar: {
        crown: `
            M 30,120 
            C 30,90 40,85 50,85
            C 60,85 70,90 70,120
            L 70,160
            C 70,190 60,195 50,195
            C 40,195 30,190 30,160
            Z
        `,
        root: `
            M 35,120
            L 38,70
            C 40,50 45,35 50,30
            C 55,35 60,50 62,70
            L 65,120
        `,
        details: `
            M 30,140
            C 40,150 60,150 70,140
            M 50,85
            L 50,195
            M 40,130
            C 45,135 55,135 60,130
            M 35,110
            L 65,110
        `
    },
    premolar: {
        crown: `
            M 35,120 
            C 35,90 45,85 50,85
            C 55,85 65,90 65,120
            L 65,160
            C 65,190 55,195 50,195
            C 45,195 35,190 35,160
            Z
        `,
        root: `
            M 45,120
            L 47,60
            C 48,40 50,30 50,25
            C 50,30 52,40 53,60
            L 55,120
        `,
        details: `
            M 35,140
            C 42,150 58,150 65,140
            M 50,85
            L 50,195
        `
    },
    canine: {
        crown: `
            M 40,120 
            C 40,90 45,85 50,85
            C 55,85 60,90 60,120
            L 60,160
            C 60,190 55,195 50,195
            C 45,195 40,190 40,160
            Z
        `,
        root: `
            M 45,120
            L 48,50
            C 49,35 50,25 50,20
            C 50,25 51,35 52,50
            L 55,120
        `,
        details: `
            M 40,140
            C 45,150 55,150 60,140
            M 50,85
            L 50,195
        `
    },
    incisor: {
        crown: `
            M 42,120 
            C 42,90 46,85 50,85
            C 54,85 58,90 58,120
            L 58,160
            C 58,190 54,195 50,195
            C 46,195 42,190 42,160
            Z
        `,
        root: `
            M 46,120
            L 48,60
            C 49,40 50,30 50,25
            C 50,30 51,40 52,60
            L 54,120
        `,
        details: `
            M 42,140
            C 46,150 54,150 58,140
            M 50,85
            L 50,195
        `
    }
};

// Function to get tooth type based on tooth number
const getToothType = (number) => {
    // Convert to 1-32 range if in FDI notation (11-48)
    const n = number > 32 ? ((number % 10) + (Math.floor(number / 10) - 1) * 8) : number;
    
    if ([1, 2, 3, 14, 15, 16, 17, 18, 19, 30, 31, 32].includes(n)) {
        return 'molar';
    } else if ([4, 5, 12, 13, 20, 21, 28, 29].includes(n)) {
        return 'premolar';
    } else if ([6, 11, 22, 27].includes(n)) {
        return 'canine';
    } else {
        return 'incisor';
    }
};
</script>

<style scoped>
.dental-chart {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
}

.teeth-row {
    margin: 20px 0;
}

.teeth-svg {
    width: 100%;
    height: auto;
}

.tooth {
    cursor: pointer;
    transition: all 0.3s ease;
}

.tooth-crown {
    fill: white;
    stroke: #666;
    stroke-width: 1.5;
}

.tooth-root {
    fill: none;
    stroke: #999;
    stroke-width: 1;
    stroke-dasharray: 2,2;
}

.tooth-detail {
    fill: none;
    stroke: #666;
    stroke-width: 1;
}

.tooth-number {
    font-size: 14px;
    fill: #666;
    text-anchor: middle;
    dominant-baseline: middle;
}

.tooth:hover .tooth-crown {
    fill: #e3f2fd;
    stroke: #2196f3;
}

.tooth:hover .tooth-root {
    stroke: #2196f3;
}

.tooth:hover .tooth-detail {
    stroke: #2196f3;
}

.tooth:hover .tooth-number {
    fill: #2196f3;
}

.tooth.selected .tooth-crown {
    fill: #2196f3;
    stroke: #1976d2;
}

.tooth.selected .tooth-root {
    stroke: #1976d2;
}

.tooth.selected .tooth-detail {
    stroke: #1976d2;
}

.tooth.selected .tooth-number {
    fill: #1976d2;
}
</style>
