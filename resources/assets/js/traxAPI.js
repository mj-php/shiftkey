

    // Mock endpoints to be changed with actual REST API implementation
let traxAPI = {
    getCarsEndpoint() {
        return '/api/get-cars'
    },
    getCarEndpoint(id) {
        return '/api/get-car' + '/' + id;
    },
    addCarEndpoint() {
        return '/api/add-car';
    },
    deleteCarEndpoint(id) {
        return '/api/delete-car' + '/' + id;
    },
    getTripsEndpoint() {
        return '/api/get-trips';
    },
    addTripEndpoint() {
        return 'api/add-trip'
    }
}



export { traxAPI };
