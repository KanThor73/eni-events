window.onload = () => {
    let categorie = document.querySelector("#city_name");
    categorie.addEventListener("change", function () {
        let form = this.closest("form");
        let data = this.name + "=" + this.value;

        fetch(form.action, {
            method: form.getAttribute("method"),
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded;charset=utf-8"
            }
        })
            .then(response => response.text())
            .then(html => {
                    let content = document.createElement("html");
                    content.innerHTML = html;
                    let newSelect = content.querySelector("#city_places");
                    document.querySelector("#city_places").replaceWith(newSelect);
                }
            )
    });
}