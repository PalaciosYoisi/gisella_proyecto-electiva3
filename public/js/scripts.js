// Pequeñas utilidades JS para la página
function scrollToDestinos(e){
  e && e.preventDefault();
  const el = document.getElementById('destinos');
  if(el) el.scrollIntoView({behavior:'smooth', block:'start'});
}

// Contador simple (data-target)
document.addEventListener('DOMContentLoaded', ()=>{
  const counters = document.querySelectorAll('.counter');
  counters.forEach(c=>{
    const target = +c.getAttribute('data-target')||0;
    let count = 0;
    const step = Math.max(1, Math.floor(target/80));
    const id = setInterval(()=>{
      count += step;
      if(count>=target){ c.textContent = target; clearInterval(id); }
      else c.textContent = count;
    }, 20);
  });
  
  // Mostrar/Ocultar segunda fila de experiencias destacadas
  const btnVerMas = document.getElementById('verMasExperiencias');
  const fila2 = document.getElementById('experienciasFila2');
  if(btnVerMas && fila2){
    btnVerMas.addEventListener('click', ()=>{
      if(getComputedStyle(fila2).display === 'none'){
        fila2.style.display = '';
        btnVerMas.innerHTML = '<i class="fas fa-minus-circle me-2"></i>Ver menos experiencias';
        fila2.scrollIntoView({behavior:'smooth', block:'start'});
      } else {
        fila2.style.display = 'none';
        btnVerMas.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Ver más experiencias';
      }
    });
  }
});
