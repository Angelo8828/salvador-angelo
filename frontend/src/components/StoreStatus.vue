<template>
  <div class="store-status">
    <h2>Bakery Status</h2>
    <p v-if="loading">Loading...</p>
    <p v-else-if="error" class="error">{{ error }}</p>
    <p v-else>
      <span v-if="status.is_open" class="open">🟢 Open</span>
      <span v-else class="closed">🔴 Closed - Reopens {{ status.next_opening }}</span>
    </p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      status: {},
      loading: true,
      error: null,
    };
  },
  mounted() {
    this.fetchStoreStatus();
  },
  methods: {
    async fetchStoreStatus() {
      try {
        const response = await axios.get(`${this.$apiBaseUrl}/store-status`);
        this.status = response.data;
      } catch (err) {
        this.error = "Failed to fetch store status.";
      } finally {
        this.loading = false;
      }
    },
  },
};
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
