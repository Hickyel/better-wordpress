function waitForElm(selector) {
    return new Promise(resolve => {
        if (document.querySelector(selector)) {
            return resolve(document.querySelector(selector));
        }

        const observer = new MutationObserver(mutations => {
            if (document.querySelector(selector)) {
                observer.disconnect();
                resolve(document.querySelector(selector));
            }
        });

        // If you get "parameter 1 is not of type 'Node'" error, see https://stackoverflow.com/a/77855838/492336
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

function obetnirMarkers(){
    let markers = fetch(`/wp-json/wpgmza/v1/markers/`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors de la récupération des marqueurs");
            }
            return response.json();
        })
        .then((data) => {
            return data;
        })
    return markers;
}

function obtenirMarker(markers,id) {
    markerSelected = undefined;
    markers.forEach((marker,index) => {
        if(marker["id"] == id){
            markerSelected = marker;
        }
    });
    return markerSelected;
}


jQuery(document).ready(async function () {

    let markers = await obetnirMarkers();

    await waitForElm("#wpgmza_marker_list_2")
    let listeMarker = jQuery("#wpgmza_marker_list_2");

    await waitForElm(".wpgmaps_blist_row");

    let listeItems = listeMarker.children();

    await waitForElm("#accordeon");

    let accordeon = jQuery("#accordeon");

    listeItems.each((key, item) => {
        item = jQuery(item);

        let id = item.attr("data-marker-id");

        marker = obtenirMarker(markers,id);

        if(marker === undefined){return;}

        if(marker["categories"] === undefined){return;}

        let categories = marker["categories"];

        if(categories[0] === undefined){return;}

        let mainCategory = categories[0];

        
        let accordeonContentByCat = jQuery(`div[aria-labelledby='category_${mainCategory}']`);

        console.log(accordeonContentByCat);

        item.find(".wpgmza-basic-list-item").css({
            "color":"white"
        });
        item.hover(function(){
            jQuery(this).find(".wpgmza-basic-list-item").css({
                "color":"black"
            });
        },function(){
            jQuery(this).find(".wpgmza-basic-list-item").css({
                "color":"white"
            });            
        });

        accordeonContentByCat.append(item);

        accordeonContentByCat.css({
            "border-top":"none",
            "border-left":"none",
            "border-right":"none" ,
            "border-bottom":"none"       
        })
    });

    listeMarker.children().remove();

    let accordeonChildren = accordeon.children();

    accordeon.append(listeMarker);
    listeMarker.append(accordeonChildren);

    accordeon.find("summary").each((index,item) => {
        jQuery(item).css({
            "border-top":"none",
            "border-left":"none",
            "border-right":"none"
        })
    });

});