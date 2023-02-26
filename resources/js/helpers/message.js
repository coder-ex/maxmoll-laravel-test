// класс работы с сообщениями
export default class Msg {
    // constructor(ref_elem) {
    //     this.elem = ref_elem;
    //     console.log(this.elem);
    // }
    constructor() {
        this.elem = null;
    }
    // get_ref() {
    //     return this.elem;
    // }
    static out_msg(status, msg, ref) {
        //let elem = this.$refs.errorMsg;
        this.elem = ref;
        this.elem.name.innerHTML = '<label style="height: 8px;" id="close-msg">' + msg + '</label>';
        if(status === 'error')
            this.elem.name.style.background = '#ff5747';
        else if(status === 'warning')
            this.elem.name.style.background = '#ffe588';
        else // ok
            this.elem.name.style.background = '#87ff61';
    }
    static clear_msg(ref) {
        //let elem = this.$refs.errorMsg;
        this.elem = ref;
        this.elem.name.innerHTML = '<label></label>';
        this.elem.name.style.background = '#f8fafc';
    }
    static out_console(status, msg) {
        console.log('---> ' + status);
        console.log(msg);
        console.log('---> ' + status);
    }
}
