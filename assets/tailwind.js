import './styles/tailwind.less';
import 'tippy.js/dist/tippy.css';

import Alpine from 'alpinejs'
import Tooltip from "@ryangjchandler/alpine-tooltip";

Alpine.plugin(Tooltip);

window.Alpine = Alpine
Alpine.start()

import './scripts/init-alpine.js'

