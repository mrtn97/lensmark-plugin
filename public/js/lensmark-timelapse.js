document.addEventListener("DOMContentLoaded", function() {
	const images = document.querySelectorAll('.timelapse-image');
	const dateText = document.getElementById('date-text');
	let currentImage = 0;
	let timer = null;
	
	function play() {
	  timer = setInterval(function() {
		images[currentImage].classList.remove('active');
		currentImage = (currentImage + 1) % images.length;
		images[currentImage].classList.add('active');
		const imageDate = images[currentImage].getAttribute('data-date');
		dateText.textContent = imageDate;
	  }, 1000);
	}
	
	function pause() {
	  clearInterval(timer);
	}
	
	function prev() {
	  images[currentImage].classList.remove('active');
	  currentImage = (currentImage - 1 + images.length) % images.length;
	  images[currentImage].classList.add('active');
	  const imageDate = images[currentImage].getAttribute('data-date');
	  dateText.textContent = imageDate;
	}
	
	function next() {
	  images[currentImage].classList.remove('active');
	  currentImage = (currentImage + 1) % images.length;
	  images[currentImage].classList.add('active');
	  const imageDate = images[currentImage].getAttribute('data-date');
	  dateText.textContent = imageDate;
	}
	
	document.getElementById('play-btn').addEventListener('click', play);
	document.getElementById('pause-btn').addEventListener('click', pause);
	document.getElementById('prev-btn').addEventListener('click', prev);
	document.getElementById('next-btn').addEventListener('click', next);
	
	images[0].classList.add('active');
	const firstImageDate = images[0].getAttribute('data-date');
	dateText.textContent = firstImageDate;
  });