# �w�q Serial Port �H�γq�T�]�w
set serialPort COM4:
set iomode "38400,n,8,1"

# �q Serial Port �����
proc GetData {channel} {
    if {[gets $channel line] > 0} {
        # ���o�@�Ӯɶ��W�O
        set now [clock seconds]
        set timestamp [clock format $now -format {%Y-%m-%d; %H:%M:%S}]
        
        # �b���쪺��Ʀ�(line)�e���[�W�ɶ��W�O��L�X
        puts "$timestamp;$line"
    }
}

# �}�� Serial Port
set channel [open $serialPort RDWR]

# �]�w Serial Port 
# �w�] 38400bps, no parity, 8-bit data, 1 stop bit
fconfigure $channel -mode $iomode -blocking 0 -buffering line

# �� Serial Port ����ƥiŪ�ɡA�۰ʩI�s GetData �����
fileevent $channel readable [list GetData $channel]

# �i�J Event-Loop �B�z file events
vwait forever