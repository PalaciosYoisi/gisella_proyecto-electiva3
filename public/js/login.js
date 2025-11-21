// Pequeño script para alternar visibilidad de contraseña en modal
document.addEventListener('click', function(e){
  if(e.target && e.target.id === 'togglePassword'){
    const input = document.getElementById('password');
    if(input){
      input.type = input.type === 'password' ? 'text' : 'password';
      e.target.querySelector('i').classList.toggle('fa-eye-slash');
    }
  }
  if(e.target && e.target.id === 'toggleRegisterPassword'){
    const input = document.getElementById('registerPassword');
    if(input){
      input.type = input.type === 'password' ? 'text' : 'password';
      e.target.querySelector('i').classList.toggle('fa-eye-slash');
    }
  }
});
