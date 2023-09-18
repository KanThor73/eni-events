window.onload = () => {
    let cityName = document.querySelector("#data_location_city");
    cityName.addEventListener("change", function () {
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
                    console.log(html);
                    let content = document.createElement("html");
                    content.innerHTML = html;
                    let newSelect = content.querySelector("#data_location_name");
                    // let newPostCode = content.querySelector("#data_location_postCode");
                    document.querySelector("#data_location_name").replaceWith(newSelect);
                    // document.querySelector("#data_location_postCode").replaceWith(newPostCode);
                    let newStreet = content.querySelector("#data_location_street");
                    document.querySelector("#data_location_street").replaceWith(newStreet);
                }
            )
    });
    //
    // let placeName = document.querySelector("#data_location_name");
    // placeName.addEventListener("change", function () {
    //     let form = this.closest("form");
    //     let data = this.name + "=" + this.value;
    //     fetch(form.action, {
    //         method: form.getAttribute("method"),
    //         body: data,
    //         headers: {
    //             "Content-Type": "application/x-www-form-urlencoded;charset=utf-8"
    //         }
    //     })
    //         .then(response => response.text())
    //         .then(html => {
    //                 console.log(html);
    //                 let content = document.createElement("html");
    //                 content.innerHTML = html;
    //                 let newStreet = content.querySelector("#data_location_street");
    //                 let newlatitude = content.querySelector("#data_location_latitude");
    //                 let newLongitude = content.querySelector("#data_location_longitude");
    //                 document.querySelector("#data_location_street").replaceWith(newStreet);
    //                 document.querySelector("#data_location_latitude").replaceWith(newlatitude);
    //                 document.querySelector("#data_location_longitude").replaceWith(newLongitude);
    //             }
    //         )
    // });
}