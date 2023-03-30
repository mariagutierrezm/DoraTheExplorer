export const Search = (function () {

    const originInput = document.getElementById("origin-input");
    const destinationInput = document.getElementById("destination-input");
    const flightTypeSelect = document.getElementById("flight-type-select");
    const departureDateInput = document.getElementById("departure-date-input");
    const returnDate = document.getElementById("return-date");
    const returnDateInput = document.getElementById("return-date-input");
    const adultsInput = document.getElementById("adults-input");
    const searchButton = document.getElementById("search-button");
    const searchResultsLoader = document.getElementById("search-results-loader");
    const searchResults = document.getElementById("search-results");
    const searchResultsSeparator = document.getElementById(
        "search-results-separator"
    );


    function init() {
        reset();
        
        document.body.addEventListener("input", () => {
            searchButton.disabled = !originInput.value || !destinationInput.value;
        });

        flightTypeSelect.addEventListener("change", () => {
            if (flightTypeSelect.value === "one-way") {
                returnDate.classList.add("d-none");
            } else {
                returnDate.classList.remove("d-none");
            }
        });

        searchButton.addEventListener("click", async () => {

            searchResultsSeparator.classList.remove("d-none");
            searchResultsLoader.classList.remove("d-none");
            searchResults.textContent = "";
            let promise;
            let data;

            if (flightTypeSelect.value === "round-trip") {
                data = getFlightsDetails(true);
                promise = getSearchAdaptor(data);
            } else {
                data = getFlightsDetails(false);
                promise = getSearchCurl(data);
            }
            
            promise.then((response) => {
                showResults(response.data);
            });
            promise.fail((error) => {
                // 'Oops seems no exploring for you.'
                $('.alert').html(error.responseJSON.message);
                $('.alert').removeClass('d-none');
            });

        });
        
        let getSearchCurl = (params) => {

            const parameters = new URLSearchParams(params);

            return $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `/search-flight?${parameters}`
            });
        }

        let getSearchAdaptor = (data) => {

            return $.ajax({
                type: 'POST',
                dataType: 'json',
                url: `/search-flight`,
                data: data
            });
        }
    }

    let getFlightsDetails = (returns) => {
        return {
            originLocationCode: originInput.value.toUpperCase(),
            destinationLocationCode: destinationInput.value.toUpperCase(),
            departureDate: formatDate(departureDateInput.valueAsDate),
            adults: formatNumber(adultsInput.value),
            ...(returns ? { returnDate: formatDate(returnDateInput.valueAsDate) } : {}),
            max: '10',
            currencyCode: 'GBP'
        };
    }

    const showResults = (results) => {
        if (results.length === 0) {
            searchResults.insertAdjacentHTML(
                "beforeend",
                `<li class="list-group-item d-flex justify-content-center align-items-center" id="search-no-results">
                    No results
                </li>`
            );
        } else {

            results.forEach(({ itineraries, price }) => {
                searchResultsLoader.classList.add("d-none");

                const priceLabel = `${price.total} ${price.currency}`;
                searchResults.insertAdjacentHTML(
                "beforeend",
                `<li class="flex-column flex-sm-row list-group-item d-flex justify-content-between align-items-sm-center">
                    ${itineraries.map((itinerary, index) => {
                        const [, hours, minutes] = itinerary.duration.match(/(\d+)H(\d+)?/);
                        const travelPath = itinerary.segments.flatMap(({ arrival, departure }, index, segments) => {
                            if (index === segments.length - 1) {
                                return [departure.iataCode, arrival.iataCode];
                            }
                            return [departure.iataCode];
                        }).join(" → ");
                        return `<div class="flex-column flex-1 m-2 d-flex">
                                    <small class="text-muted">${
                                    index === 0 ? "Outbound" : "Return"
                                    }</small>
                                    <span class="fw-bold">${travelPath}</span>
                                    <div>${hours || 0}h ${minutes || 0}m</div>
                                </div>`;
                        }).join("")}
                    <span class="bg-primary rounded-pill m-2 badge fs-6">${priceLabel}</span>
                    </li>`
                );
            });
        }
    }

    const formatDate = (date) => {
        const [formattedDate] = date.toISOString().split("T");
        return formattedDate;
    };

    const formatNumber = (number) => {
        return `${Math.abs(parseInt(number))}`;
    };

    const reset = () => {
        {
            originInput.value = "";
            destinationInput.value = "";
            flightTypeSelect.value = "one-way";
            departureDateInput.valueAsDate = new Date();
            returnDateInput.valueAsDate = new Date();
            returnDate.classList.add("d-none");
            adultsInput.value = 1;
            searchButton.disabled = true;
            searchResultsSeparator.classList.add("d-none");
            searchResultsLoader.classList.add("d-none");
        };
    }
   
    return {
        init: init
    }
}());

