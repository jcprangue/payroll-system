<template>
	<div>
		<v-row v-if="hasAction">
            <v-col>
                <gmap-place-input 
                    :default-place="defaultAddress"
                    class="v-form-text" 
                    placeholder="Search your location . . ." 
                    @place_changed="getAddressData" />
            </v-col>
            <v-col cols="12" lg="auto">
		        <v-btn @click="addMarkerOnMyCoordinates" color="red lighten-3">PLACE MARKER</v-btn>
            </v-col>
        </v-row>
		<GmapMap
			ref="mapRef"
            class="mt-2"
			:zoom="15"
			map-type-id="roadmap"
			:center="computedCoordinates"
			style="width: 100%; height: 300px; border: 1px solid rgba(0,0,0,0.25)"
		>
        <GmapMarker
            v-if="computedMarker"
            :position="computedMarker.position"
            :clickable="true"
            :draggable="true"
            @dragend="onMarkerDrag"
        />
		</GmapMap>
	</div>
</template>

<script>
    import {gmapApi} from 'vue2-google-maps'
	export default {
		name: "GoogleMap",
        props: {
            coordinates: {
                type: Object,
                default: () => { return { lat: 10.0, lng: 10.0 }},
            },
            marker: {
                type: Object,
                default: () => { return { lat: 10.0, lng: 10.0 }},
            },
            hasAction: {
                type: Boolean,
                default: true
            },
            defaultAddress: String
        },
        computed: {
            google: gmapApi,
            computedCoordinates: {
                set: function(val){
                    this.$emit('update:coordinates', val)
                },
                get: function(){
                    return this.coordinates
                }
            },
            computedMarker: {
                set: function(val)
                {
                    this.$emit('update:marker',val)
                },
                get: function(){
                    return this.marker
                }
            }
        },
		methods: {
            getAddressData: function(addressData)
            {
                this.computedCoordinates = {
                    lat: addressData.geometry.location.lat(),
                    lng: addressData.geometry.location.lng(),
                };
                let address = {
                    fomatted_address: '',
                    postal_code: '',
                    street_number: '',
                    country: '',
                    city : '',
                };

                address.fomatted_address = addressData.formatted_address
                for (let index = 0; index < addressData.address_components.length; index++) {
                    if(addressData.address_components[index].types.includes("country"))
                    {
                        address.country = addressData.address_components[index].long_name
                    }
                    if(addressData.address_components[index].types.includes("locality"))
                    {
                        address.city = addressData.address_components[index].long_name
                    }
                    if(addressData.address_components[index].types.includes("street_number"))
                    {
                        address.street_number = addressData.address_components[index].long_name
                    }
                    if(addressData.address_components[index].types.includes("postal_code"))
                    {
                        address.postal_code = addressData.address_components[index].long_name
                    }
                }
                this.$emit('addressChange', address);
                this.$emit('update:defaultAddress', address.fomatted_address);
            },
            onMarkerDrag: function(event)
            {
                this.computedCoordinates = event.latLng;
            },
            addMarkerOnMyCoordinates(){
                var vm = this;
                vm.computedMarker = {position: vm.computedCoordinates}
            },
			fetchMyLocation: function () {
				var vm = this;
                let options = {
                    enableHighAccuracy: true
                }
				this.$getLocation(options).then((coordinates) => {
					vm.computedCoordinates = coordinates;
				});
			},
		},
	};
</script>

<style lang="css" scoped>
    .pac-target-input
    {
        width: 100%;   
    }
</style>