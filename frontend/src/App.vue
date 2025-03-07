<template>
  <div>
    <h1>Bakery</h1>
    <StoreStatus />
    <DateChecker />
  </div>
</template>

<script>
import StoreStatus from './components/StoreStatus.vue';
import DateChecker from './components/DateChecker.vue';

export default {
  components: { StoreStatus, DateChecker },
};
</script>

<style>
body {
  font-family: Arial, sans-serif;
  text-align: center;
  margin: 20px;
}
</style>
