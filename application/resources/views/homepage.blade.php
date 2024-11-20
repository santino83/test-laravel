
<x-public-layout>

    <div class="container px-3" style="margin: 0 auto">
        <h1>Breweries</h1>
        <div id="table-container" class="p-4">
            <table class="w-full table-auto border-collapse border border-gray-200">
                <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Brewery Type</th>
                    <th class="border border-gray-300 px-4 py-2">Address 1</th>
                    <th class="border border-gray-300 px-4 py-2">Country</th>
                    <th class="border border-gray-300 px-4 py-2">State</th>
                    <th class="border border-gray-300 px-4 py-2">City</th>
                    <th class="border border-gray-300 px-4 py-2">Street</th>
                    <th class="border border-gray-300 px-4 py-2">Postal Code</th>
                    <th class="border border-gray-300 px-4 py-2">Phone</th>
                    <th class="border border-gray-300 px-4 py-2">Website</th>
                </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>
            <div id="loading" class="text-center" style="display: none">Loading</div>
            <div class="flex justify-between mt-4">
                <button id="prev-page" class="bg-blue-500 text-black/50 px-4 py-2 rounded disabled:opacity-50" disabled>Previous</button>
                <button id="next-page" class="bg-blue-500 text-black/50 px-4 py-2 rounded disabled:opacity-50" disabled>Next</button>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        const tableBody = document.getElementById('table-body');
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const loading = document.getElementById('loading');
        const token = @js($token);

        async function fetchBreweries(askedPage = 1) {
            try {
                loading.style.display = '';

                const response = await window.axios.get(`/api/breweries?page=${askedPage}`,{
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const { perPage, page, list } = response.data;

                currentPage = page;

                tableBody.innerHTML = '';

                list.forEach(brewery => {
                    const row = document.createElement('tr');
                    const cssClass = 'border border-gray-300 px-4 py-2';
                    row.innerHTML = `
                        <td class="${cssClass}">${brewery.id}</td>
                        <td class="${cssClass}">${brewery.name}</td>
                        <td class="${cssClass}">${brewery.breweryType}</td>
                        <td class="${cssClass}">${brewery.address1}</td>
                        <td class="${cssClass}">${brewery.country}</td>
                        <td class="${cssClass}">${brewery.state}</td>
                        <td class="${cssClass}">${brewery.city}</td>
                        <td class="${cssClass}">${brewery.street}</td>
                        <td class="${cssClass}">${brewery.postalCode}</td>
                        <td class="${cssClass}">${brewery.phone}</td>
                        <td class="${cssClass}">${brewery.websiteUrl}</td>
                    `;
                    tableBody.appendChild(row);
                });

                loading.style.display = 'none';
                prevPageBtn.disabled = page <= 1;
                nextPageBtn.disabled = list.length === 0;
            } catch (error) {
                console.error('Error fetching breweries:', error);
                alert('Failed to load breweries.');
            }
        }

        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                fetchBreweries(currentPage - 1);
            }
        });

        nextPageBtn.addEventListener('click', () => {
            fetchBreweries(currentPage + 1);
        });

        // Initial fetch
        document.addEventListener('DOMContentLoaded', () => {
            fetchBreweries();
        });
    </script>

</x-public-layout>
