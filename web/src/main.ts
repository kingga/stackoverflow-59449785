import { AxiosHttp } from '@kingga/kc-http';
import LaravelEcho from 'laravel-echo';
import Pusher from 'pusher-js';
import Vue from 'vue';

import App from './App.vue';

Vue.config.productionTip = false;
(window as any).Pusher = Pusher;

new Vue({
  render: h => h(App)
}).$mount("#app");

// 1. Login.
window.onload = () => {
  (async () => {
    const baseURL = "http://so-echo.test/api";
    const http = new AxiosHttp({ baseURL });

    const loginResponse = await http.post({
      url: "login",
      body: {
        email: "joe.king@example.com",
        password: "password"
      }
    });

    const token = loginResponse.data.token;
    const user = loginResponse.data.user;

    console.log({
      loginResponse,
      token,
      user
    });

    http.setHeader("Authorization", `Bearer ${token}`);

    // 2. Setup Laravel Echo.
    const Echo = new LaravelEcho({
      broadcaster: "pusher",
      key: process.env.VUE_APP_PUSHER_KEY,
      cluster: "ap1",
      encrypted: true,
      authEndpoint: `${baseURL}/broadcasting/auth`
    });

    Echo.connector.pusher.config.auth.headers.Authorization = `Bearer ${token}`;

    console.log({ Echo });

    // 3. Subscribe.
    Echo.private(`App.User.${user.id}`).listen(".friend.request", (data: any) =>
      console.log({ data })
    );
  })();
};
