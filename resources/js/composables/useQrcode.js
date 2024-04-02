import { ref } from "vue"

export default function useQrcode() {

	const camera = ref("auto");
	const qrLoading = ref(false);
	const qrError = ref("");
	const qrStatus = ref("Camera is off!");
	const qrStatusColor = ref("text-danger");

	const onInit = async (promise) => {
		qrLoading.value = true;
		try {
				await promise
		}catch (error) {
				if (error.name === 'NotAllowedError') {
						qrError.value = "ERROR: you need to grant camera access permission"
				} else if (error.name === 'NotFoundError') {
						qrError.value = "ERROR: no camera on this device"
				} else if (error.name === 'NotSupportedError') {
						qrError.value = "ERROR: secure context required (HTTPS, localhost)"
				} else if (error.name === 'NotReadableError') {
						qrError.value = "ERROR: is the camera already in use?"
				} else if (error.name === 'OverconstrainedError') {
						qrError.value = "ERROR: installed cameras are not suitable"
				} else if (error.name === 'StreamApiNotSupportedError') {
						qrError.value = "ERROR: Stream API is not supported in this browser"
				}
		} finally {
				qrLoading.value = false;
		}
	}

	const openCamera = () => {
		camera.value = "auto";
	}

	const offCamera = () => {
		camera.value = "off";
		qrStatusColor.value = "text-danger";
		qrStatus.value ="Camera is off!";
	}

	return {
		camera,
		qrLoading,
		qrError,
		qrStatus,
		qrStatusColor,
		onInit,
		openCamera,
		offCamera,
	}
}