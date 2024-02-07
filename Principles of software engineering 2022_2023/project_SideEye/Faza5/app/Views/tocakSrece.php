<!--Lana Ivković-->


<section>
    <body>
    
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?= base_url("lana/css/spin.css")?>">
    
    
    <button  class="btn" id="spin" >Spin</button>
    
   
	
    
	<span class="arrow"></span>
<div class="row">
<form action="<?= base_url("KorisnikController/tocakSrece")?>" method="POST" id="myForm">
    <input type="submit" disabled  id="spin_dugme" style="justify-content:start; width: 200px; height: 40px; margin-top: 30px;"  value="Preuzmi nagradu">
    <a href="<?= base_url("KorisnikController")?>"><input type="button"   id="home_spin" style="margin-left:100px; width: 200px; height: 40px; margin-top: 30px;"  value="Home"></a>
    <div class="container col-xl-8 col-lg-8 col-md-8 col-sm-12">
        <div class="one" > <span id="one" >20%</span></div> 
        <div class="eight" > <span id="eight">20€</span></div>
        <div class="seven" > <span id="seven">40€</span></div>
        <div class="six" ><span id="six">10%</span></div>
        <div class="five" ><span id="five">5%</span></div>
        <div class="four"><span id="four">nista</span></div>
        <div class="three" ><span id="three">15€</span></div>
        <div class="two" > <span id="two">8%</span></div>
    </div>
    
    
    
<div class="justify-content-center" style=" margin: auto; width: 50%;" ;>
<label style="display:flex; justify-content: center; font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif;" >Rezultat: </label>
<div class=" justify-content-center" style="display:flex; justify-content: center;" > <input name="poklon" id="dobitak" value="20%" readonly ></div>

<label style="display:flex; justify-content: center; font-weight: bold; font-family: Georgia, 'Times New Roman', Times, serif;" >Preostalo: </label>
<div class=" justify-content-center" style="display:flex; justify-content: center;" ><span name="poklon" id="tokeni"  readonly ><?=$tokena?> </span> </div>

</form>



</div>

</body>

<script src="<?= base_url("lana/js/spininjs.js")?>">






</script>
    <style>
    #spin_dugme{
        border-radius: 4px; box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        background-color: pink;
         color: aliceblue;
        cursor: pointer;
        transition: background-color 0.3s, border-color 0.3s; border: 1px solid wheat;
    }
    #home_spin{
        border-radius: 4px; box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        background-color: pink;
         color: aliceblue;
        cursor: pointer;
        transition: background-color 0.3s, border-color 0.3s; border: 1px solid wheat;
    }
    #dobitak{
        background-color: #40E0D0;
        color: black;
        text-decoration: solid;
        border: none;   
        border-radius: 4px;
        width: 100px;
        font-size: 16px;
        text-align: center;
    }
    #tokeni{
        background-color: #40E0D0;
        color: black;
        border: none;
        text-decoration: solid;

        border-radius: 4px;
        width: 100px;
        font-size: 16px;
        text-align: center;
    }
    

  
</style>

</section>
