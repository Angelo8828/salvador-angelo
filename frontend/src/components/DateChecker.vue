<template>
  <div>
    <h3>Check Store Availability</h3>
    <VueDatePicker v-model="selectedDate" @update:modelValue="checkDate" />
    <p v-if="result">{{ result }}</p>
  </div>
</template>

<script>
import axios from 'axios';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
  components: { VueDatePicker },
  data() {
    return {
      selectedDate: null,
      result: "",
    };
  },
  methods: {
    async checkDate() {
      if (!this.selectedDate) return;
      try {
        const response = await axios.get(`${this.$apiBaseUrl}/check-date`, {
          params: { date: this.selectedDate.toISOString().split('T')[0] },
        });
        this.result = response.data.is_open
          ? "✅ The bakery is open!"
          : `❌ Closed - Opens in ${response.data.next_opening}`;
      } catch {
        this.result = "Error checking date.";
      }
    },
  },
};
</script>

<style scoped>
h3 {
  margin-bottom: 10px;
}
</style>
