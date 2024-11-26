import { htmlentities } from "../../../lib/js/htmlentities.js"
import { Sesion } from "../Sesion.js"
import { ROL_ID_ADMINISTRADOR } from "../ROL_ID_ADMINISTRADOR.js"
import { ROL_ID_CAPITAN } from "../ROL_ID_CAPITAN.js"
import { ROL_ID_SUPERADMIN } from "../ROL_ID_SUPERADMIN.js"

export class MiNav extends HTMLElement {

 connectedCallback() {
  this.style.display = "block"

  this.innerHTML = /* html */
   `<nav class="mt-6">
     <ul class="flex flex-col p-0 list-none">
      <li><progress max="100" class="w-full">Cargando…</progress></li>
     </ul>
    </nav>`
 }

 /**
  * @param {Sesion} sesion
  */
 set sesion(sesion) {
  const correo = sesion.correo
  const rolIds = sesion.rolIds
  let innerHTML = this.createNavItem("../dashboard/inicio.html", "fas fa-home", "Inicio")
  innerHTML += this.hipervinculosAdmin(rolIds)
  innerHTML += this.hipervinculosCliente(rolIds)
  innerHTML += this.hipervinculosSuperAdmin(rolIds)
  const correoHtml = htmlentities(correo)
  // if (correo !== "") {
  //  innerHTML += this.createNavItem("#", "fas fa-user", correoHtml)
  // }

  console.log(rolIds);
  

  if (rolIds.has(ROL_ID_ADMINISTRADOR)) {
    innerHTML += this.createNavItem("../admin/ligas.html", "fas fa-users-cog", "Ligas")
  }else if(rolIds.has(ROL_ID_CAPITAN)){
    innerHTML += this.createNavItem("../capitan/suscripcion.html", "fas fa-users-cog", "Ligas")
  }

  innerHTML += this.createNavItem("../dashboard/perfil.html?correo=" + correo, "fas fa-trophy", "Perfil")
  innerHTML += this.createNavItem("#", "fas fa-cog", "Configuración")
  innerHTML += this.createNavItem("#", "fas fa-cog", "LogOut", `onclick="consumeJson('../../srv/auth/logout.php')
     .then(json => location.reload())
     .catch(muestraError)"`)
  const ul = this.querySelector("ul")
  if (ul !== null) {
   ul.innerHTML = innerHTML
  }
 }

 /**
  * @param {string} href
  * @param {string} iconClass
  * @param {string} text
  */
 createNavItem(href, iconClass, text, event = '') {
  return /* html */`
   <li>
    <a href="${href}" class="flex items-center py-3 px-6 text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition-colors duration-200" ${event}>
     <i class="${iconClass} mr-3"></i>
     ${text}
    </a>
   </li>`
 }

 /**
  * @param {Set<string>} rolIds
  */
 hipervinculosAdmin(rolIds) {
  if (rolIds.has(ROL_ID_ADMINISTRADOR)) {
   return /* html */`
    ${this.createNavItem("admin-usuarios.html", "fas fa-user-shield", "Albitros")}
   `
  }
  return ""
 }

 /**
  * @param {Set<string>} rolIds
  */
 hipervinculosSuperAdmin(rolIds) {
  if (rolIds.has(ROL_ID_SUPERADMIN)) {
   return /* html */`
    ${this.createNavItem("../super-admin/usuarios.html", "fas fa-user-shield", "Usuarios")}
   `
  }
  return ""
 }

 /**
  * @param {Set<string>} rolIds
  */
 hipervinculosCliente(rolIds) {
  if (rolIds.has(ROL_ID_CAPITAN)) {
    return /* html */`
     ${this.createNavItem("../capitan/equipos.html", "fas fa-users", "Equipos")}
    `
   }
   return ""
 }
}

customElements.define("mi-nav", MiNav)