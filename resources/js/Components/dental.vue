<template>
    <svg width="500" height="700" xmlns="http://www.w3.org/2000/svg">
        <!-- Upper teeth -->
        <g id="upper-teeth">
            <!-- Teeth group: Upper left (1-16) -->
            <text x="60" y="50" font-size="12">18</text>
            <circle
                cx="60"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <text x="100" y="50" font-size="12">17</text>
            <circle
                cx="100"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <text x="140" y="50" font-size="12">16</text>
            <circle
                cx="140"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <!-- Highlighted tooth -->
            <text x="180" y="50" font-size="12">15</text>
            <circle
                cx="180"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="#A9D5FF"
            />

            <text x="220" y="50" font-size="12">14</text>
            <circle
                cx="220"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <!-- Repeat for other upper teeth (13-11, then 21-28) -->

            <!-- Teeth group: Upper right (21-28) -->
            <text x="260" y="50" font-size="12">13</text>
            <circle
                cx="260"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <text x="300" y="50" font-size="12">12</text>
            <circle
                cx="300"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="#A9D5FF"
            />

            <text x="340" y="50" font-size="12">11</text>
            <circle
                cx="340"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="#A9D5FF"
            />
        </g>

        <!-- Lower teeth -->
        <g id="lower-teeth" transform="translate(0, 400)">
            <!-- Teeth group: Lower left (48-41) -->
            <text x="60" y="50" font-size="12">48</text>
            <circle
                cx="60"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <text x="100" y="50" font-size="12">47</text>
            <circle
                cx="100"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <text x="140" y="50" font-size="12">46</text>
            <circle
                cx="140"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <!-- Repeat for other lower teeth (45-41, then 31-38) -->

            <!-- Teeth group: Lower right (31-38) -->
            <text x="300" y="50" font-size="12">32</text>
            <circle
                cx="300"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />

            <text x="340" y="50" font-size="12">31</text>
            <circle
                cx="340"
                cy="70"
                r="15"
                stroke="black"
                stroke-width="1"
                fill="none"
            />
        </g>

        <!-- Midline -->
        <line
            x1="200"
            y1="20"
            x2="200"
            y2="680"
            stroke="black"
            stroke-width="1"
        />
    </svg>
</template>
<style></style>
