(function () {
  const cfg = window.GOPHER_CONFIG;
  const frame = document.getElementById("gopher-frame");
  const status = document.getElementById("gopher-status");
  const openLink = document.getElementById("gopher-open-tab");

  if (!cfg || !frame) return;

  const embedUrl =
    cfg.proxyOrigin +
    cfg.embedPath +
    "?url=" +
    encodeURIComponent(cfg.startGopher);

  frame.src = embedUrl;
  if (openLink) openLink.href = embedUrl;

  frame.addEventListener("load", function () {
    if (status) status.classList.add("hidden");
  });

  frame.addEventListener("error", function () {
    if (status) {
      status.textContent =
        "Could not load the Gopher proxy. Deploy gopher-proxy (see github.com/wrstone/gopher-proxy) or open with ?proxy=http://127.0.0.1:8765";
      status.classList.remove("hidden");
    }
  });
})();