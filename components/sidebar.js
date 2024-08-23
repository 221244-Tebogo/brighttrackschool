document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const handle = document.querySelector(".handle");
  let isResizing = false;

  const initResize = (e) => {
    isResizing = true;
    document.body.style.cursor = "col-resize";
    window.addEventListener("mousemove", resize);
    window.addEventListener("mouseup", stopResize);
  };

  const resize = (e) => {
    if (!isResizing) return;
    let newWidth = e.clientX - sidebar.offsetLeft;
    if (newWidth < 60) newWidth = 60;
    if (newWidth > 260) newWidth = 260;
    sidebar.style.width = `${newWidth}px`;
  };

  const stopResize = () => {
    isResizing = false;
    document.body.style.cursor = "default";
    window.removeEventListener("mousemove", resize);
    window.removeEventListener("mouseup", stopResize);
  };

  handle.addEventListener("mousedown", initResize);
});
