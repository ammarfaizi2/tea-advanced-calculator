<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		* {
			font-family: Arial;
		}
		button {
			cursor: pointer;
		}
		.sol {
			border: 1px solid #000;
			max-width: 900px;
			height: auto;
			margin-top: 10px;
			padding: 10px;
		}
		.sol2 {
			border: 1px solid #000;
			max-width: 900px;
			height: auto;
			margin-top: 10px;
			padding: 10px;
		}
		#form {
			margin-bottom: 100px;
		}
		img {
			padding: 10px;
			background-color: #fff;
		}
	</style>
</head>
<body>
	<center>
		<h1>Tea Advanced Calculator</h1>
		<form id="form" method="post" action="javascript:void(0);">
			<div>
				<h3>Enter math expression here</h3>
				<textarea id="expr" style="width: 351px; height: 107px;"></textarea>
			</div>
			<div>
				<button type="submit">Calculate</button>
			</div>
			<div id="solutions"></div>
		</form>
	</center>
	<script type="text/javascript">
		const surl = "https://api.teainside.org/latex_x.php?exp=",
			apiurl = "https://api.teainside.org/teacalc2.php?key=8e7eaa2822cf3bf77a03d63d2fbdeb36df0a409f&expr=";

		let f = document.getElementById("form"),
			q = document.getElementById("expr"),
			s = document.getElementById("solutions"),
			step_counter = 0,
			hsl_val = 350,
			recursive_counter = 0;

		function expand_step_class(el, cls) {
			if (el.innerHTML == "<h4>Expand Steps</h4>") {
				el.innerHTML = "<h4>Hide Steps</h4>";
			} else {
				el.innerHTML = "<h4>Expand Steps</h4>";
			}
			let i, x = document.getElementsByClassName(cls);
			for (i in x) {
				if (typeof x[i].style != "undefined") {
					if (x[i].style.display) {
						x[i].style.display = "";
					} else {
						x[i].style.display = "none";
					}
				}
			}
		}

		function recstep(div, steps, _sid) {
			let sid, i, iimg, ddiv, 
				aa = document.createElement("a"),
				hh = document.createElement("h4");
			hh.appendChild(document.createTextNode("Expand Steps"));
			aa.appendChild(hh);
			aa.href = "javascript:void(0);";
			aa.setAttribute("onclick", "expand_step_class(this, \""+_sid+"\");");
			div.appendChild(aa);
			div.style["background-color"] = "hsl("+hsl_val+", 86%, 91%)";
			hsl_val -= 30;

			for (i in steps) {
				iimg = document.createElement("img");
				ddiv = document.createElement("div");
				if (typeof steps[i].step_input == "undefined") {
					iimg.src = surl+encodeURIComponent(steps[i].entire_result);
				} else {
					iimg.src = surl+encodeURIComponent(steps[i].step_input+steps[i].entire_result);
				}
				ddiv.appendChild(iimg);
				ddiv.setAttribute("class", "sol2 "+_sid);
				ddiv.style.display = "none";
				div.appendChild(ddiv);
				if ((typeof steps[i].steps != "undefined")) {
					recstep(ddiv, steps[i].steps, _sid+"-"+i);
				}
			}
		}

		function applySolution(dq, sol)
		{
			let i, c = 0, img, div;
			for (i in sol) {
				img = document.createElement("img");
				div = document.createElement("div");
				if (c == 0) {
					img.src = surl+encodeURIComponent(q.value+sol[i].entire_result);
				} else {
					img.src = surl+encodeURIComponent(sol[i].step_input+sol[i].entire_result);
				}
				div.appendChild(img);
				div.setAttribute("class", "sol");
				dq.appendChild(div);
				recstep(div, sol[i].steps, "step_"+i);
				step_counter++;
				c++;
			}
		}

		f.addEventListener("submit", function () {
			f.disabled = q.disabled = 1;
			s.innerHTML = "<h2>Calculating...</h2>";
			let ch = new XMLHttpRequest;
				ch.onload = function () {
					s.innerHTML = "<h2>Answer</h2>";
					s.style.display = "";
					let x = JSON.parse(this.responseText);
					hsl_val = 350;
					recursive_counter = step_counter = 0;
					applySolution(s, x.solutions);
					f.disabled = q.disabled = 0;
				};
				ch.open("GET", apiurl+encodeURIComponent(q.value));
				ch.send(null);
		});
	</script>
</body>
</html>