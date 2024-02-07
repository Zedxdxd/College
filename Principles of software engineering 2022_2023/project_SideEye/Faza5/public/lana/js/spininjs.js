$(document).ready(function () {
	let container = document.querySelector(".container");
	let btn = $('#spin');
	let number = Math.ceil(Math.random() * 1000);

	$('#spin').click(function () {



		myButton = $('#spin');
		myButton.prop('disabled', true);
		if (parseInt($("#tokeni").html()) == 0) {
			alert("Nema tokena")
			return;
		}

		console.log(parseInt($("#tokeni").html()));
		var podaci = "nista";
		console.log("teodora");

		container.style.transform = "rotate(" + number + "deg)";
		broj = (number + 22.5) % 360 / 45;
		number += Math.ceil(Math.random() * 1000);

		if (parseInt(broj) != 5) {
			var p = {
				broj: podaci,

			};

		}
		axios.post('/KorisnikController/metoda', { parametar: podaci })
			.then(function (response) {
				console.log(response.data);
				console.log("uspijeh");
			})
			.catch(function (error) {
				console.log(error);
				console.log("error");
			});

		setTimeout(function () {
			//vrati dugme;

			
			switch (parseInt(broj)) {
				case 0:
					document.getElementById("dobitak").innerHTML = "20%";
					podaci = "20%";
					break;
				case 1:
					document.getElementById("dobitak").innerHTML = "1000din";
					podaci = "20€";
					break;
				case 2:
					document.getElementById("dobitak").innerHTML = "2000din";
					podaci = "40€";
					break;
				case 3:
					document.getElementById("dobitak").innerHTML = "10%";
					podaci = "10%";
					break;
				case 4:
					document.getElementById("dobitak").innerHTML = "5%";
					podaci = "5%"
					break;
				case 5:
					document.getElementById("dobitak").innerHTML = "nista";
					break;
				case 6:
					document.getElementById("dobitak").innerHTML = "1500din";
					podaci = "15€";
					break;
				case 7:
					document.getElementById("dobitak").innerHTML = "8%";
					podaci = "8%";
					break;



			}

			$("#dobitak").val(podaci);

			myButton = $('#spin_dugme');
			if (myButton.prop('disabled')) {
				myButton.prop('disabled', false);

			}





		}, 3000)
	})

})


