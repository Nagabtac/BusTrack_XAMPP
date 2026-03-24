<div class="toolbar">
    <h2 class="section-title">Passenger Management</h2>
    <button class="btn" onclick="loadPassengers()">Refresh</button>
</div>

<input
    type="text"
    id="passengerSearch"
    class="search-input"
    placeholder="Search by ID, name, contact, pickup, dropoff"
    oninput="loadPassengers()"
>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Pickup</th>
                <th>Dropoff</th>
                <th>Travel Date</th>
                <th>Payment</th>
                <th>Fare</th>
            </tr>
        </thead>
        <tbody id="passengersTable"></tbody>
    </table>
</div>

<script>
function loadPassengers() {
    const searchInput = document.getElementById('passengerSearch');
    const q = searchInput ? searchInput.value.trim() : '';
    const url = 'api/passengers.php?action=list&q=' + encodeURIComponent(q);

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const table = document.getElementById('passengersTable');
            if (!data.success || !Array.isArray(data.data)) {
                table.innerHTML = '<tr><td colspan="10">Failed to load passengers.</td></tr>';
                return;
            }

            if (data.data.length === 0) {
                table.innerHTML = '<tr><td colspan="10">No passengers found.</td></tr>';
                return;
            }

            let html = '';
            data.data.forEach(passenger => {
                html += `
                <tr>
                    <td>${passenger.passenger_id ?? ''}</td>
                    <td>${passenger.full_name ?? ''}</td>
                    <td>${passenger.age ?? ''}</td>
                    <td>${passenger.gender ?? ''}</td>
                    <td>${passenger.contact_number ?? ''}</td>
                    <td>${passenger.pickup_location ?? ''}</td>
                    <td>${passenger.dropoff_location ?? ''}</td>
                    <td>${passenger.travel_date ?? ''}</td>
                    <td>${passenger.payment_method ?? ''}</td>
                    <td>${passenger.fare_amount ?? ''}</td>
                </tr>`;
            });

            table.innerHTML = html;
        })
        .catch(() => {
            document.getElementById('passengersTable').innerHTML =
                '<tr><td colspan="10">Error loading passengers.</td></tr>';
        });
}

loadPassengers();
</script>
