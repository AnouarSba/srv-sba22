
<style>
html {
  background: url("https://assets.codepen.io/6060109/salaries-background.png") no-repeat right bottom;
  background-size: cover;
  font-family: Hellix;
}

body {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

.container {
  width: 450px;
  height: 400px;
  text-align: center;
}

.company {
  background: #FFFFFF;
  box-shadow: 0px 24px 32px rgba(0, 0, 0, 0.04), 0px 16px 24px rgba(0, 0, 0, 0.04), 0px 4px 8px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
  border-radius: 5px;
  display: flex;
  align-items: center;
  padding: 10px 30px;
  position: relative;
}
.company > * {
  padding: 0px 10px;
}

.company h3:last-of-type {
  position: absolute;
  right: 10%;
}

.company img {
  width: 20px;
}
</style>
<div class="" style="width:10%">
<form action="{{url('export')}}" method="POST">
  @csrf
  <input hidden type="datetime-local" class="form-control" id="game-date-time-text" name="start_date" value="{{ $from }}" >
  <input hidden type="datetime-local" class="form-control" id="game-date-time-text" name="end_date" value="{{ $to }}" >
  <input type="submit" value="Export Excel Data">
</form>
</div> 
<div class="container" style="display:flex; width:100%">
  
<div class="row col-12" style="display:flex; width:100%">

 <div class="container col-4" style="width: 33%; display: inline-block; 
    top: 250px;
    ">
  <h2>Top Flexy Ranking</h2>
  <h4>Client</h4>
  <ol class="companies">
  </ol>
</div>
<div class="container col-4" style="width: 33%; display: inline-block; 
    top: 250px;
    ">
  <h2>Top Flexy Ranking</h2>
  <h4>Receveur</h4>
  <ol class="companies_rec">
  </ol>
</div>
<div class="container col-4" style="width: 33%; display: inline-block; 
    top: 250px;
    ">
  <h2>Top Cart Vent</h2>
  <h4>Controller</h4>
  <ol class="companies_c">
  </ol>
</div>
</div>
</div>

<script>
var companies = <?php echo json_encode( $top ); ?>;
var companiesElement = document.querySelector('.companies');
var i=0;
// Use pattern matching to add name and salary as h3's
// Then add an img in the right place, between rank and name  <img src="${company.logo}">
for (let company of companies) {
    i++;
  var newCompanyElement = `
    <li class="company">
      <h3>${i}.</h3>
    
      <h3>${company.client['name']}</h3>
      <h3>${company.flexy}</h3>
    </li>
    <p>${company.client['phone']} &nbsp; ${company.client['email']}</p>
    `;
  companiesElement.innerHTML += newCompanyElement;
}

var companies = <?php echo json_encode( $top_rec ); ?>;
console.log(companies);
var companiesElement = document.querySelector('.companies_rec');
var i=0;
// Use pattern matching to add name and salary as h3's
// Then add an img in the right place, between rank and name  <img src="${company.logo}">
for (let company of companies) {
    i++;
  var newCompanyElement = `
    <li class="company">
      <h3>${i}.</h3>
    
      <h3>${company.name}</h3>
      <h3>${company.flexy}</h3>
    </li>
    `;
  companiesElement.innerHTML += newCompanyElement;
}

var companies = <?php echo json_encode( $cartvent ); ?>;
var companiesElement = document.querySelector('.companies_c');
var i=0;
// Use pattern matching to add name and salary as h3's
// Then add an img in the right place, between rank and name  <img src="${company.logo}">
for (let company of companies) {
    i++;
  var newCompanyElement = `
    <li class="company">
      <h3>${i}.</h3>
    
      <h3>${company.name}</h3>
      <h3>${company.cmpt}</h3>
    </li>
    `;
  companiesElement.innerHTML += newCompanyElement;
}

</script>