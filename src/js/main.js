// Load header and footer components, wire nav toggle, set footer year
async function loadComponent(id, url){
  const el = document.getElementById(id);
  if(!el) return;
  try{
    const res = await fetch(url);
    if(!res.ok) throw new Error('Not found');
    el.innerHTML = await res.text();
  }catch(e){
    console.warn('Component load failed', url, e);
  }
}

document.addEventListener('DOMContentLoaded', async ()=>{
  await loadComponent('componentHeader','/src/components/header.html');
  await loadComponent('componentFooter','/src/components/footer.html');

  // footer year
  const y = document.getElementById('year'); if(y) y.textContent = new Date().getFullYear();

  // nav toggle
  const navToggle = document.getElementById('navToggle');
  navToggle?.addEventListener('click', ()=>{
    const nav = document.getElementById('mainNav');
    if(!nav) return;
    nav.style.display = (getComputedStyle(nav).display === 'flex') ? 'none' : 'flex';
  });

  // simple fade-in animation for cards
  requestAnimationFrame(()=>{
    document.querySelectorAll('.card, .feature, .hero-content').forEach((n,i)=>{n.style.transform='translateY(0)';n.style.opacity='1';});
  });
});
