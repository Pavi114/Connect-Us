var loc = document.querySelector('button[name="loc"]');
var place = document.querySelector('input[name="place"]');
var removeLoc = document.querySelector('#removeLoc');

var placeSearch = document.createElement("INPUT");
placeSearch.classList.add("form-control");
placeSearch.name = "placeSearch"; 
var defaultBounds = new google.maps.LatLngBounds(
	new google.maps.LatLng(23.63936, 68.14712),
	new google.maps.LatLng(28.20453, 97.34466));

var searchBox = new google.maps.places.SearchBox(placeSearch, {
	bounds: defaultBounds
});
place.addEventListener("click",function(){
	
	removeLoc.parentNode.insertBefore(placeSearch,removeLoc.nextSibling);

})

searchBox.addListener("places_changed",function(){
	console.log(searchBox.getPlaces());
	var places = searchBox.getPlaces();
	if(places[0].hasOwnProperty('formatted_address')){
		place.value = places[0].formatted_address;
	}
	else {
	  place.value = places[0].name;	
	}
});

loc.addEventListener("click",function(){
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(getPlace);
	}
	else {
		alert('geolocation not supported');
	}
})
	

function getPlace(position){
	$.ajax({
		type: 'GET',
		url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${position.coords.latitude},${position.coords.longitude}&key=${PLACES_KEY}&result_type=locality|administrative_area_level_1|administrative_area_level_2|administrative_area_level_3`,
		success: function(response){
			console.log(response);
			place.value = response.results[0].formatted_address;
		},
		error: function(){
			alert('something went wrong');
		}
	});
}