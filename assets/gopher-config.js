// Gopher proxy settings for wrstone.github.io
// Override at runtime: gopher.html?proxy=https://your-host.example.com
(function () {
  const params = new URLSearchParams(window.location.search);
  const override = params.get("proxy");
  const local =
    location.hostname === "localhost" || location.hostname === "127.0.0.1";

  const PROXY_ORIGINS = [
    override,
    local ? "http://127.0.0.1:8765" : null,
    "https://wrstone-gopher-proxy.onrender.com",
    "https://gopher.wrstone.com",
  ].filter(Boolean);

  window.GOPHER_CONFIG = {
    proxyOrigin: (PROXY_ORIGINS[0] || "https://wrstone-gopher-proxy.onrender.com").replace(/\/$/, ""),
    startGopher: "gopher://sdf.org/users/wrstone/",
    embedPath: "/embed",
  };
})();