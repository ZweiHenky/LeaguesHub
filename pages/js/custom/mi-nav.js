import { htmlentities } from "../../../lib/js/htmlentities.js"
import { Sesion } from "../Sesion.js"
import { ROL_ID_ADMINISTRADOR } from "../ROL_ID_ADMINISTRADOR.js"
import { ROL_ID_CAPITAN } from "../ROL_ID_CAPITAN.js"

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
  const correoHtml = htmlentities(correo)
  // if (correo !== "") {
  //  innerHTML += this.createNavItem("#", "fas fa-user", correoHtml)
  // }
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
    ${this.createNavItem("../admin/ligas.html", "fas fa-users-cog", "Ligas")}
    ${this.createNavItem("admin-usuarios.html", "fas fa-user-shield", "Albitros")}
   `
  }
  return ""
 }

 /**
  * @param {Set<string>} rolIds
  */
 hipervinculosCliente(rolIds) {
  return rolIds.has(ROL_ID_CAPITAN) ?
   this.createNavItem("cliente.html", "fas fa-users", "Equipos")
   : ""
 }
}

customElements.define("mi-nav", MiNav)