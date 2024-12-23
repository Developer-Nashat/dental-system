<script setup lang="ts">
import { onMounted, ref } from "vue";
import { Tooth } from "../types/Tooth";

const selectedTooth = ref<Tooth | null>(null);
// const teeth = ref<Tooth[]>([]);

const props = defineProps({
  isBottom: {
    type: Boolean,
    default: false,
  },

  teeth: {
    type: Array as () => Tooth[],
    required: true,
  },

  isClickable: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["selectedTooth"]);

const selectTooth = (tooth: Tooth) => {
  if (props.isClickable) {
    props.teeth.forEach((t) => {
      t.hasProblem = false; // Reset all teeth
    });
    tooth.hasProblem = true; // Set the clicked tooth as having a problem
    selectedTooth.value = tooth; // Update the selected tooth
    emit("selectedTooth", selectedTooth.value);
  }
};
</script>

<template>
  <div class="teeth-row" :class="isBottom ? 'lower' : 'upper'">
    <div
      v-for="tooth in props.teeth"
      :key="tooth.tooth_number"
      class="tooth"
      :class="{ 'cursor-arrow': !isClickable }"
      @click="isClickable ? selectTooth(tooth) : null"
    >
      <div
        v-if="!isBottom"
        class="tooth-number"
        :class="tooth.hasProblem ? 'text-red' : ''"
      >
        {{ tooth.tooth_number }}
      </div>
      <!-- <v-tooltip
                :text="tooth.tooth_number.toString()"
                activator="parent"
                :location="isBottom ? 'bottom' : 'top'"
            >
            </v-tooltip> -->
      <v-icon size="40" :color="tooth.hasProblem ? 'red' : 'primary'"
        >mdi-tooth-outline</v-icon
      >
      <div
        v-if="isBottom"
        class="tooth-number"
        :class="tooth.hasProblem ? 'text-red' : ''"
      >
        {{ tooth.tooth_number }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.teeth-row {
  display: flex;
  justify-content: space-between;
  padding: 1rem;
}

.tooth {
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;

  border-radius: 4px;
  transition: all 0.3s ease;
}

.tooth:hover {
  background-color: rgba(63, 81, 181, 0.05);
}

.tooth.has-problem {
  background-color: rgba(244, 67, 54, 0.1);
}

.tooth-number {
  font-size: 0.75rem;
  color: rgba(0, 0, 0, 0.6);
}

.upper {
  border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}

.lower {
  border-top: 2px solid rgba(0, 0, 0, 0.1);
}
.cursor-arrow {
  cursor: default; /* or 'not-allowed' if you want to indicate it's not clickable */
}
</style>
