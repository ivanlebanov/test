<template>
<div id="page">
  <main class="main">
    <div class="container">
      <div class="panel" v-if="average">
          <h1>Fun facts about your store</h1>
          <p>Customers spend <strong>£{{ average }}</strong> on average.</p>
      </div>
      <div class="panel half" v-if="users.length > 0">
        <h2>The lads and their average order.</h2>
        <ul class="user-list">
          <li v-for="user in users">
            <strong>{{ user.customer.first_name }} {{ user.customer.last_name }}</strong> has spend <strong>£{{ user.average }}</strong> on average.
          </li>
        </ul>
      </div>
      <div class="panel half" v-if="line_items.length > 0">
        <h2>Mean average order value of a specific variant</h2>
        <ul class="user-list">
          <li v-for="line in line_items">
            <strong>{{ line.variant_title }}</strong> average is <strong>£{{ line.average }}</strong>.
          </li>
        </ul>
      </div>
    </div>
  </main>
</div>
</template>
<script>
import axios from "axios";
export default {
  name: "App",
  data() {
    return {
      average: null,
      users: [],
      line_items: []
    };
  },
  mounted() {
    axios.get("/api/orders/average").then(r => (this.average = r.data));
    axios.get("api/orders/customers/average").then(r => (this.users = r.data));
    axios.get("/api/line_items/average").then(r => (this.line_items = r.data));
  }
};
</script>
<style lang="scss">
.panel {
  margin: 20px 0;
  background: #fff;
  padding: 20px;
  border-radius: 4px;
  float: left;
  width: 100%;
  &.half {
    width: calc(50% - 30px);
    margin-left: 15px;
    margin-right: 15px;
  }
}
.user-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
</style>
