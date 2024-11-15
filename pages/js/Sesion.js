import { exportaAHtml } from "../../lib/js/exportaAHtml.js"
import { CORREO } from "./CORREO.js"
import { ROL_IDS } from "./ROL_IDS.js"

export class Sesion {

 /**
  * @param { any } objeto
  */
 constructor(objeto) {

  /**
   * @readonly
   */
  this.correo = objeto[CORREO]
  
  if (typeof this.correo !== "string")
   throw new Error("cue debe ser string.")

  /**
   * @readonly
   */
  const rolIds = objeto[ROL_IDS]
  if (!Array.isArray(rolIds))
   throw new Error("rolIds debe ser arreglo.")
  /**
   * @readonly
   */
  this.rolIds = new Set(rolIds)

  
    console.log(this.rolIds); 

 }

}



exportaAHtml(Sesion)