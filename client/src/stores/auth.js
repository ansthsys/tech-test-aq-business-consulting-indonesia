import { ref } from "vue";
import { defineStore } from "pinia";
import { jwtDecode } from "jwt-decode";

export const useAuthStore = defineStore("auth", () => {
  const dataToken = ref({});
  const isAuthenticated = ref(false);

  function verifyToken() {
    const token = localStorage.getItem("token");

    if (token) {
      const decodedToken = jwtDecode(token, { header: false });

      if (decodedToken.exp > Date.now() / 1000) {
        isAuthenticated.value = true;
        dataToken.value = decodedToken;
      }
    }
  }

  function revokeToken() {
    localStorage.removeItem("token");
    isAuthenticated.value = false;
    window.location.href = "/login";
  }

  return {
    dataToken,
    isAuthenticated,
    verifyToken,
    revokeToken,
  };
});
