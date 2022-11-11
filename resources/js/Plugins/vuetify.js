import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faBars, faBook, faCalculator, faCalendar, faCamera, faCaretDown, faCheck, faCheckSquare, faChevronDown, faChevronLeft, faChevronRight, faCross, faDollarSign, faEye, faEyeSlash, faFile, faHeart, faHistory, faHome, faInfo, faInfoCircle, faLock, faMapMarker, faPaperclip, faPencilAlt, faShoppingCart, faSign, faSignOutAlt, faSortUp, faThLarge, faTimes, faTimesCircle, faTrash, faUnlock, faUser, faUserAlt } from '@fortawesome/free-solid-svg-icons'
import { faFacebook, faGithub, faGooglePlusG, faInstagram, faTwitter } from '@fortawesome/free-brands-svg-icons'
import { faSquare } from '@fortawesome/free-regular-svg-icons'

Vue.component('font-awesome-icon', FontAwesomeIcon) // Register component globally
Vue.component('v-icon', FontAwesomeIcon) // Register component globally
library.add(faBars) // Include needed icons
library.add(faHome)
library.add(faSortUp)
library.add(faChevronLeft)
library.add(faChevronRight)
library.add(faChevronDown)
library.add(faHistory)
library.add(faInfo)
library.add(faCaretDown)
library.add(faShoppingCart)
library.add(faHeart)
library.add(faEye)
library.add(faEyeSlash)
library.add(faPencilAlt)
library.add(faBook)
library.add(faGooglePlusG)
library.add(faFacebook)
library.add(faTwitter)
library.add(faMapMarker)
library.add(faInstagram)
library.add(faSquare)
library.add(faCheckSquare)
library.add(faUser)
library.add(faSignOutAlt)
library.add(faTimesCircle)
library.add(faPaperclip)
library.add(faCamera)
library.add(faGithub)
library.add(faLock)
library.add(faTrash)
library.add(faThLarge)
library.add(faUserAlt)
library.add(faFile)
library.add(faCheck)
library.add(faTimes)
library.add(faInfoCircle)
library.add(faCalendar)
library.add(faDollarSign)
library.add(faCalculator)
library.add(faSign)
library.add(faLock)
library.add(faUnlock)


Vue.use(Vuetify)

const opts = {
  icons: {
    iconfont: 'faSvg',
  },
}

export default new Vuetify(opts)