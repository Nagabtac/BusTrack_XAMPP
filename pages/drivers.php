<h2 class="section-title">Driver Management</h2>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>License</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="driversTable"></tbody>
    </table>
</div>

<script>
function loadDrivers(){
    fetch('api/drivers.php?action=list')
    .then(res=>res.json())
    .then(data=>{
        let html='';
        data.data.forEach(driver=>{
            html+=`
            <tr>
                <td>${driver.driver_name}</td>
                <td>${driver.license_number}</td>
                <td><span class="badge">${driver.status}</span></td>
            </tr>`;
        });
        document.getElementById('driversTable').innerHTML = html;
    });
}

loadDrivers();
</script>