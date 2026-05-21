let zones = document.querySelectorAll(".zone");

zones.forEach(zone => {
    let level = parseInt(zone.getAttribute("data-level"));

    if (level >= 3) {
        zone.classList.add("danger");
    } else if (level == 2) {
        zone.classList.add("warning");
    } else {
        zone.classList.add("safe");
    }
});