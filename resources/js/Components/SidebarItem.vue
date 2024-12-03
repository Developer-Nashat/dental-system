<script setup lang="ts">
import { ref, inject } from "vue";

interface MenuItem {
    id: number;
    label: string;
    icon?: string;
    children?: MenuItem[];
}

const props = defineProps<{
    item: MenuItem;
    collapsed?: boolean;
}>();

const activeItemId = inject("activeItemId", ref(1));
const setActiveItem = inject("setActiveItem") as (id: number) => void;

const isExpanded = ref(false);

const toggleExpand = (event: Event) => {
    if (props.collapsed) return;
    event.stopPropagation();
    isExpanded.value = !isExpanded.value;
};

const handleClick = (event: Event) => {
    event.stopPropagation();
    setActiveItem(props.item.id);
    if (props.item.children?.length && !props.collapsed) {
        toggleExpand(event);
    }
};

const isActive = (item: MenuItem): boolean => {
    if (item.id === activeItemId.value) return true;
    return !!item.children?.some((child) => isActive(child));
};
</script>

<template>
    <div class="sidebar-item">
        <div
            class="item-header"
            :class="{
                'has-children': item.children?.length,
                active: isActive(item),
                collapsed: collapsed,
            }"
            @click="handleClick"
        >
            <div class="item-content">
                <span class="icon" v-if="item.icon">{{ item.icon }}</span>
                <span class="label" v-show="!collapsed">{{ item.label }}</span>
            </div>
            <span
                v-if="item.children?.length && !collapsed"
                class="arrow"
                :class="{ expanded: isExpanded }"
            >
                ▶
            </span>
        </div>

        <div
            v-if="item.children?.length && !collapsed"
            class="submenu"
            :class="{ expanded: isExpanded }"
        >
            <SidebarItem
                v-for="child in item.children"
                :key="child.id"
                :item="child"
                :collapsed="collapsed"
            />
        </div>
    </div>
</template>

<style scoped>
.sidebar-item {
    width: 100%;
}

.item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s;
    color: var(--text-secondary);
    border-left: 3px solid transparent;
    margin: 2px 0;
}

.item-header:hover {
    background-color: var(--hover-color);
    color: var(--primary-color);
}

.item-header.active {
    background-color: var(--hover-color);
    border-left-color: var(--primary-color);
    color: var(--primary-color);
}

.item-content {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.label {
    font-weight: 500;
    font-size: 0.95rem;
}

.arrow {
    font-size: 0.8em;
    transition: transform 0.2s;
    opacity: 0.5;
}

.arrow.expanded {
    transform: rotate(90deg);
}

.item-header:hover .arrow {
    opacity: 1;
}

.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    padding-right: 32px;
}

.submenu.expanded {
    max-height: 500px;
}

.item-header.collapsed {
    justify-content: center;
    padding: 12px 0;
}

.item-header.collapsed .icon {
    margin: 0;
}
</style>
