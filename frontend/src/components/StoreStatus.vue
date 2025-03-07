<template>
  <div>
    <h2 v-if="storeStatus.is_open === 'open'">🟢 Open</h2>
    <h2 v-else>🔴 Closed - Opens {{ nextOpeningMessage }}</h2>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const storeStatus = ref({ is_open: false, next_opening: null });
const nextOpeningMessage = ref("");

const fetchStoreStatus = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8080/api/store-status");
    storeStatus.value = response.data;

    // Handle the "Opens in undefined" issue
    if (storeStatus.value.is_open) {
      nextOpeningMessage.value = ""; // Don't show any message
    } else if (storeStatus.value.next_opening) {
      nextOpeningMessage.value = `on ${storeStatus.value.next_opening}`;
    } else {
      nextOpeningMessage.value = "at an unknown time"; // Fallback message
    }
  } catch (error) {
    console.error("Failed to fetch store status", error);
  }
};

onMounted(fetchStoreStatus);
</script>

<style scoped>
.store-status {
  border: 1px solid #ccc;
  padding: 10px;
  border-radius: 5px;
  text-align: center;
  width: 250px;
}
.open {
  color: green;
  font-weight: bold;
}
.closed {
  color: red;
  font-weight: bold;
}
.error {
  color: red;
}
</style>
