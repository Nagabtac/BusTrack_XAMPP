<div class="cards">
    <div class="card">
        <h3>Total Buses</h3>
        <p id="totalBuses">-</p>
    </div>
    <div class="card">
        <h3>Total Drivers</h3>
        <p id="totalDrivers">-</p>
    </div>
</div>

<script>
fetch('api/dashboard.php')
.then(res=>res.json())
.then(data=>{
    if(data.success){
        document.getElementById('totalBuses').textContent = data.data.totalBuses;
        document.getElementById('totalDrivers').textContent = data.data.totalDrivers;
    }
});
</script>