<div class="toolbar">
    <h2 class="section-title">Bus Management</h2>
    <button class="btn" onclick="loadBuses()">Refresh</button>
</div>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Bus #</th>
                <th>Plate</th>
                <th>Model</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="busesTable"></tbody>
    </table>
</div>

<script>
function loadBuses(){
    fetch('api/buses.php?action=list')
    .then(res=>res.json())
    .then(data=>{
        let html = '';
        data.data.forEach(bus=>{
            html += `
            <tr>
                <td>${bus.bus_number}</td>
                <td>${bus.plate_number}</td>
                <td>${bus.model}</td>
                <td><span class="badge">${bus.status}</span></td>
            </tr>`;
        });
        document.getElementById('busesTable').innerHTML = html;
    });
}

loadBuses();
</script>