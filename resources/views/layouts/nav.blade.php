<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

$routename = str_replace('#','', Route::currentRouteName());

?>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="bootstrap" viewBox="0 0 118 94">
		<title>Bootstrap</title>
		<path fill-rule="evenodd" clip-rule="evenodd" d="M113.87 20.05c0.36,7.98 3.13,53.27 -0.04,57.46 -3.62,4.77 -37.16,-1.21 -44.69,-1.84 -20.96,-1.73 -35.58,-1.64 -56.49,1.02 -13.58,1.73 -13.07,-6.52 -12.35,-19.23 0.28,-4.83 4.29,-39.69 6.39,-42.13 2.42,-2.81 30.14,-3.47 35.59,-4.14 10.05,-1.25 64.36,-13.11 68.32,-10.92 4.94,2.73 3.14,14.61 3.27,19.78zm-76.86 5.58l0 3.75 -9.74 0 0 27.2 -4.33 0 0 -27.2 -9.73 0 0 -3.75 23.8 0zm18.45 26.66c-0.3,-0.71 -0.69,-1.62 -1.19,-2.72 -0.49,-1.11 -1.02,-2.3 -1.58,-3.58 -0.57,-1.28 -1.17,-2.59 -1.81,-3.95 -0.64,-1.35 -1.24,-2.63 -1.81,-3.84 -0.57,-1.21 -1.09,-2.29 -1.58,-3.24 -0.5,-0.95 -0.89,-1.68 -1.19,-2.19 -0.33,3.52 -0.59,7.32 -0.8,11.41 -0.21,4.1 -0.39,8.23 -0.54,12.4l-4.24 0c0.12,-2.68 0.25,-5.38 0.4,-8.11 0.15,-2.72 0.32,-5.4 0.51,-8.04 0.2,-2.63 0.41,-5.2 0.63,-7.7 0.22,-2.5 0.47,-4.87 0.74,-7.1l3.79 0c0.81,1.31 1.67,2.86 2.59,4.64 0.93,1.79 1.85,3.66 2.77,5.61 0.93,1.95 1.82,3.9 2.68,5.85 0.87,1.95 1.65,3.73 2.37,5.34 0.71,-1.61 1.5,-3.39 2.37,-5.34 0.86,-1.95 1.75,-3.9 2.68,-5.85 0.92,-1.95 1.84,-3.82 2.76,-5.61 0.93,-1.78 1.79,-3.33 2.6,-4.64l3.79 0c1.01,9.97 1.77,20.29 2.28,30.95l-4.24 0c-0.15,-4.17 -0.33,-8.3 -0.54,-12.4 -0.21,-4.09 -0.48,-7.89 -0.8,-11.41 -0.3,0.51 -0.7,1.24 -1.19,2.19 -0.49,0.95 -1.02,2.03 -1.58,3.24 -0.57,1.21 -1.17,2.49 -1.81,3.84 -0.64,1.36 -1.24,2.67 -1.81,3.95 -0.57,1.28 -1.09,2.47 -1.59,3.58 -0.49,1.1 -0.88,2.01 -1.18,2.72l-3.48 0zm32.2 1.21c4.53,0 6.79,-1.55 6.79,-4.65 0,-0.95 -0.2,-1.76 -0.61,-2.43 -0.4,-0.67 -0.94,-1.25 -1.63,-1.74 -0.68,-0.5 -1.46,-0.92 -2.34,-1.28 -0.88,-0.35 -1.81,-0.71 -2.79,-1.07 -1.13,-0.39 -2.2,-0.83 -3.22,-1.32 -1.01,-0.49 -1.89,-1.07 -2.63,-1.74 -0.75,-0.67 -1.33,-1.47 -1.77,-2.39 -0.43,-0.92 -0.64,-2.04 -0.64,-3.35 0,-2.71 0.92,-4.82 2.76,-6.34 1.85,-1.52 4.4,-2.28 7.64,-2.28 1.88,0 3.58,0.2 5.12,0.61 1.53,0.4 2.65,0.84 3.37,1.31l-1.39 3.53c-0.62,-0.39 -1.55,-0.77 -2.79,-1.14 -1.23,-0.37 -2.67,-0.56 -4.31,-0.56 -0.83,0 -1.61,0.09 -2.32,0.27 -0.71,0.18 -1.34,0.45 -1.88,0.81 -0.53,0.35 -0.96,0.81 -1.27,1.36 -0.31,0.55 -0.47,1.2 -0.47,1.94 0,0.83 0.17,1.53 0.49,2.1 0.33,0.57 0.79,1.06 1.39,1.5 0.59,0.43 1.29,0.82 2.07,1.18 0.79,0.36 1.66,0.71 2.62,1.07 1.34,0.54 2.57,1.07 3.68,1.61 1.12,0.54 2.09,1.18 2.91,1.92 0.81,0.74 1.45,1.63 1.89,2.66 0.45,1.02 0.67,2.27 0.67,3.73 0,2.71 -0.99,4.79 -2.97,6.25 -1.98,1.46 -4.77,2.19 -8.37,2.19 -1.22,0 -2.35,-0.08 -3.37,-0.25 -1.03,-0.16 -1.95,-0.35 -2.75,-0.58 -0.8,-0.22 -1.5,-0.46 -2.08,-0.71 -0.58,-0.26 -1.03,-0.47 -1.36,-0.65l1.3 -3.57c0.68,0.38 1.72,0.82 3.12,1.29 1.4,0.48 3.11,0.72 5.14,0.72z"></path>
	</symbol>
	<symbol id="home" viewBox="0 0 16 16">
		<path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z" />
	</symbol>
	<symbol id="speedometer2" viewBox="0 0 16 16">
		<path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z" />
		<path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z" />
	</symbol>
	<symbol id="table" viewBox="0 0 16 16">
		<path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z" />
	</symbol>
	<symbol id="people-circle" viewBox="0 0 16 16">
		<path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
		<path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
	</symbol>
	<symbol id="grid" viewBox="0 0 16 16">
		<path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z" />
	</symbol>
	<symbol id="collection" viewBox="0 0 16 16">
		<path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13z" />
	</symbol>
	<symbol id="calendar3" viewBox="0 0 16 16">
		<path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
		<path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
	</symbol>
	<symbol id="chat-quote-fill" viewBox="0 0 16 16">
		<path d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM7.194 6.766a1.688 1.688 0 0 0-.227-.272 1.467 1.467 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 5.734 6C4.776 6 4 6.746 4 7.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.461 2.461 0 0 0-.227-.4zM11 9.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.466 2.466 0 0 0-.228-.4 1.686 1.686 0 0 0-.227-.273 1.466 1.466 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 10.07 6c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z" />
	</symbol>
	<symbol id="cpu-fill" viewBox="0 0 16 16">
		<path d="M6.5 6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z" />
		<path d="M5.5.5a.5.5 0 0 0-1 0V2A2.5 2.5 0 0 0 2 4.5H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2A2.5 2.5 0 0 0 4.5 14v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14a2.5 2.5 0 0 0 2.5-2.5h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14A2.5 2.5 0 0 0 11.5 2V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1V.5zm1 4.5h3A1.5 1.5 0 0 1 11 6.5v3A1.5 1.5 0 0 1 9.5 11h-3A1.5 1.5 0 0 1 5 9.5v-3A1.5 1.5 0 0 1 6.5 5z" />
	</symbol>
	<symbol id="gear-fill" viewBox="0 0 16 16">
		<path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
	</symbol>
	<symbol id="speedometer" viewBox="0 0 16 16">
		<path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z" />
		<path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z" />
	</symbol>
	<symbol id="toggles2" viewBox="0 0 16 16">
		<path d="M9.465 10H12a2 2 0 1 1 0 4H9.465c.34-.588.535-1.271.535-2 0-.729-.195-1.412-.535-2z" />
		<path d="M6 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm.535-10a3.975 3.975 0 0 1-.409-1H4a1 1 0 0 1 0-2h2.126c.091-.355.23-.69.41-1H4a2 2 0 1 0 0 4h2.535z" />
		<path d="M14 4a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
	</symbol>
	<symbol id="tools" viewBox="0 0 16 16">
		<path d="M1 0L0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.356 3.356a1 1 0 0 0 1.414 0l1.586-1.586a1 1 0 0 0 0-1.414l-3.356-3.356a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0zm9.646 10.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708zM3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026L3 11z" />
	</symbol>
	<symbol id="chevron-right" viewBox="0 0 16 16">
		<path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
	</symbol>
	<symbol id="geo-fill" viewBox="0 0 16 16">
		<path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z" />
	</symbol>
</svg>
<style>
	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		-o-box-sizing: border-box;
		box-sizing: border-box;
		/* adds animation for all transitions */
		-webkit-transition: .25s ease-in-out;
		-moz-transition: .25s ease-in-out;
		-o-transition: .25s ease-in-out;
		transition: .25s ease-in-out;
		margin: 0;
		padding: 0;
		-webkit-text-size-adjust: none;
	}

	/* Makes sure that everything is 100% height */

	html,
	body {
		height: 100%;
		overflow: hidden;
	}

	/* gets the actual input out of the way; 
	we're going to style the label instead */

	#drawer-toggle {
		position: absolute;
		opacity: 0;
	}

	#drawer-toggle-label {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		left: 9px;
		height: 50px;
		width: 50px;
		display: block;
		position: fixed;
		background: transparent;
		z-index: 1;
	}

	/* adds our "hamburger" menu icon */

	#drawer-toggle-label:before {
		content: '';
		display: block;
		position: absolute;
		height: 2px;
		width: 24px;
		background: #fff;
		left: 13px;
		top: 18px;
		box-shadow: 0 6px 0 #fff, 0 12px 0 #fff;
	}

	header {
		width: 100%;
		position: fixed;
		left: 0px;
		background: #efefef;
		padding: 10px 10px 10px 50px;
		font-size: 30px;
		line-height: 30px;
		z-index: 0;
	}

	/* drawer menu pane - note the 0px width */

	#drawer {
		position: fixed;
		top: 0;
		left: -300px;
		height: 100%;
		width: 300px;		
		overflow-x: hidden;
		overflow-y: hidden;
		padding: 20px;
		-webkit-overflow-scrolling: touch;
	}

	/* actual page content pane */

	#page-content {
		margin-left: 0px;		
		width: 100%;
		height: 100vh;
		overflow-x: hidden;
		overflow-y: scroll;
		-webkit-overflow-scrolling: touch;
		padding: 0px 60px;
	}

	/* checked styles (menu open state) */
	.nav-pills {
		position: fixed;
		left: 10px;
	}
	
	.nav-logo{
		position: fixed;
		/* left: 17px; */
		right: 40px;
		top: 2.5%;
		width: 50px !important;
		/* bottom: 30%; */
		border-radius: 4px;
	}
	.nav-name{
		display: none;
	}
	
	.nav-pills li a.activeLink .menu-icon{
		color: var(--warning-c) !important;
	}
	.nav-link svg{
		stroke: white !important;
	}
	.menu-icon{
		color:white !important;
		font-size: 30px;
	}
	#drawer-toggle:checked~#drawer-toggle-label {
		height: 100%;
		width: calc(100% - 300px);
		background: transparent;
	}


	#drawer-toggle:checked~#drawer-toggle-label,
	#drawer-toggle:checked~header {
		left: 300px;
	}

	#drawer-toggle:checked~#drawer div .nav-pills,#drawer-toggle:checked~#drawer div div span a .nav-logo {
		position: static;
		left: auto;
	}
	#drawer-toggle:checked~#drawer div .nav-pills li a.activeLink .nav-name,
	 #drawer-toggle:checked~#drawer div .nav-pills li a.activeLink .menu-icon {
		color:var(--warning-c) !important;
	}
	#drawer-toggle:checked~#drawer div .nav-pills li a .nav-name{
		display: inline-block;
		color:var(--primary-c)
	}
	
	#drawer-toggle:checked~#drawer div .nav-pills li a .menu-icon{
		color: var(--primary-c) !important;
		display: inline-flex !important;
		justify-content: center !important;
		padding: 5px 5px;
		font-size: 18px;
		width: 26px;
		border-radius: 8px;
	}

	#drawer-toggle:checked~#drawer {
		left: 0px;
	}

	#drawer-toggle:checked~#page-content {
		margin-left: 300px;
	}

	/* Menu item styles */

	#drawer ul {
		list-style-type: none;
	}

	#drawer ul a {
		display: block;
		padding: 10px;
		color: #c7c7c7;
		text-decoration: none;
	}

	#drawer ul a:hover {
		color: white;
	}

	/* Responsive MQ */

	@media all and (max-width:350px) {

		#drawer-toggle:checked~#drawer-toggle-label {
			height: 100%;
			width: 50px;
		}

		#drawer-toggle:checked~#drawer-toggle-label,
		#drawer-toggle:checked~header {
			left: calc(100% - 50px);
		}

		#drawer-toggle:checked~#drawer {
			width: calc(100% - 50px);
			padding: 20px;
		}

		#drawer-toggle:checked~#page-content {
			margin-left: calc(100% - 50px);
		}

	}
</style>
<!-- <main class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
	
</main> 
<div class="w-100" style="height: 62px;"></div>
<nav class="navbar w-100 mx-auto d-flex justify-content-center py-2 px-4 pb-0 shadow-sm" style="position: fixed;top:0px; z-index: 8;">
	<div class="background-primary-light container-fluid rounded-lg">
		<a href="/" class="d-flex navbar-brand  align-items-center py-2 link-dark text-decoration-none">
			<i class="bi bi-flag"></i>
			<h6 class="px-2 mb-0 mt-2w-100  py-2 ">{{ucfirst($routename)}}</h6>
		</a>
		<button id="btnMenu" class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
			<i class="text-white bi bi-justify"></i>
		</button>
	</div>
</nav> -->
<input type="checkbox" id="drawer-toggle" name="drawer-toggle" />
<label for="drawer-toggle" id="drawer-toggle-label"></label>
<nav id="drawer" class="background-primary">
	<div class="bg-white rounded-lg"> 

	
	<div class="offcanvas-header bg-white justify-between">
		<span>
			<a href="/" class="d-flex  align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none ">
				<img src="{{asset('images/nicare_logo.jpg')}}" style="width: 35px;" class="nav-logo">
				<span class="fs-6 ms-2">FMS</span>
			</a>
		</span>
		
		<label for="drawer-toggle" class="btn-close" id="drawer-toggle-label2"></label>		
	</div>
	<hr class="m-0">
	<div class=" offcanvas-body d-flex flex-column flex-shrink-0 p-3 bg-white w-100 position-relative">
		<ul class="nav nav-pills flex-column mb-auto">
			<li class="nav-item">
				<a href="/home" class="nav-link {{$routename =='home'? 'activeLink text-white':'text-dark' }}" aria-current="page">
					<!-- <svg class="bi me-2" width="16" height="16">
						<use xlink:href="#home" />
					</svg> -->
					<i class="fa fa-home menu-icon"></i>
					<span class="nav-name">Home</span>
				</a>
			</li>
			<li>
				<a href="{{route('dashboard')}}" class="nav-link {{$routename =='dashboard'? 'activeLink text-white':'text-dark' }}  link-dark">
					<!-- <svg class="bi me-2" width="16" height="16">
						<use xlink:href="#speedometer2" />
					</svg> -->
					<i class="fa fa-dashboard menu-icon"></i>
					<span class="nav-name">Dashboard</span>
				</a>
			</li>

			<li>
				<a href="{{route('user')}}" class="nav-link {{$routename =='user'? 'activeLink text-white':'text-dark' }}  link-dark">
					<!-- <svg class="bi me-2" width="16" height="16">
						<use xlink:href="#grid" />
					</svg> -->
					<i class="fa fa-user menu-icon d-flex justify-content-center"></i>
					<span class="nav-name">User</span>
				</a>
			</li>
			<li class="nav-item dropdown ">
				<a href="{{route('account')}}" class="nav-link {{$routename =='account'? 'activeLink text-white':'text-dark' }}   link-dark" href="#" id="navbarDropdownMenuLink">
					<!-- <svg class="bi me-2" width="16" height="16">
						<use xlink:href="#table" />
					</svg> -->
					<i class="fa fa-table menu-icon d-flex justify-content-center"></i>
					<span class="nav-name">Account</span>
				</a>
				<!--
					<ul class="nav nav-pills flex-column mb-auto">
						<li class="nav-item"><a class="nav-link {{$routename =='dashboard'? 'activeLink text-white':'text-dark' }}  link-dark" href="#"><i class="bi bi-chevron-right"></i> New</a></li>
						<li class="nav-item"><a class="nav-link {{$routename =='dashboard'? 'activeLink text-white':'text-dark' }}  link-dark" href="#"><i class="bi bi-chevron-right"></i> View</a></li>					
					</ul> 
				-->
			</li>
			<!-- <li>
				<a href="{{route('user')}}" class="nav-link {{$routename =='user'? 'activeLink text-white':'text-dark' }}  link-dark">
					<svg class="bi me-2" width="16" height="16">
						<use xlink:href="#people-circle" />
					</svg>
					<span class="nav-name">Users</span>
				</a>
			</li> -->

		</ul>
		<hr>
		<div class="dropdown">
			<a style="cursor: pointer;" tabindex="-1" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2">
				<img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
				<strong>mdo</strong>
			</a>
			<ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">				
				<li><a class="dropdown-item nav-item" href="#">Profile</a></li>
				<li>
					<hr class="dropdown-divider">
				</li>
				<li>
					<form action="/logout" method="post">
						<button type="submit" class="dropdown-item">Sign out</button>
					</form>
				</li>
			</ul>
		</div>
	</div>
	</div>
</nav>

<script>
	/* global bootstrap: false */
	(function() {
		'use strict'
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		tooltipTriggerList.forEach(function(tooltipTriggerEl) {
			new bootstrap.Tooltip(tooltipTriggerEl)
		})


		$('#btnMenu').click(function() {
			if ($('.modal-backdrop').is(':visible')) {
				$('.modal-backdrop').removeClass('show');

			}
		})
		/*  $("#dropdownUser2").click(function(e){		
		e.stopPropagation();
		if(e.target !== e.currentTarget){
			$(this).parent().next().toggleClass('show');
		}else{
			$(this).next().toggleClass('show');
		}
	  }); */
	})()
</script>

<style>
	.mainMenu {}

	.subMenu {
		display: none;
	}
</style>