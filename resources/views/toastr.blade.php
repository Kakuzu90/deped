<script>
    @if ($message = Session::get("success"))
        NotificationApp.send("{{ $message[0] }}", "{{ $message[1] }}","top-right","#5ba035","success")
    @endif
    @if ($message = Session::get("info"))
        NotificationApp.send("{{ $message[0] }}", "{{ $message[1] }}","top-right","#3b98b5","info")
    @endif
    @if ($message = Session::get("warning"))
        NotificationApp.send("{{ $message[0] }}", "{{ $message[1] }}","top-right","#da8609","warning")
    @endif
    @if ($message = Session::get("danger"))
        NotificationApp.send("{{ $message[0] }}", "{{ $message[1] }}","top-right","#ee2b48","error")
    @endif
    @error("verify_password")
        NotificationApp.send("Password Error", "{{ $message }}","top-right","#ee2b48","error")
    @enderror
    @error("password")
        NotificationApp.send("Password Error", "{{ $message }}","top-right","#ee2b48","error")
    @enderror
</script>