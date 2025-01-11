import ax from "axios";

const axios = ax.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
    Authorization: `Bearer ${localStorage.getItem("token")}`,
  },
});

axios.interceptors.request.use(
  (conf) => conf,
  function (error) {
    return Promise.reject(error);
  }
);

export default axios;
