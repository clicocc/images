Green_font_prefix="\033[32m"
Red_font_prefix="\033[31m"
Green_background_prefix="\033[42;37m"
Red_background_prefix="\033[41;37m"
Font_color_suffix="\033[0m"
INFO="[${Green_font_prefix}INFO${Font_color_suffix}]"
ERROR="[${Red_font_prefix}ERROR${Font_color_suffix}]"

if [[ $(uname -s) != Linux ]]; then
    echo -e "${ERROR} 不支持您的系统！"
    exit 1
fi

if [[ $(id -u) != 0 ]]; then
    echo -e "${ERROR} 没有以root运行！"
    exit 1
fi


mkdir temp
cd temp
echo -e "${INFO} 处理DNS中"
rm -rf /etc/resolv.conf
echo "nameserver 2001:67c:2b0::6" >> /etc/resolv.conf
echo -e "${INFO} 系统更新中"
apt update
apt-get install wget
echo -e "${INFO} DNS已处理，安装Warp-Go中"
wget https://cdn.jsdelivr.net/gh/clicocc/images/images/2021/07/16/warp-go.sh?PKey=null -O warp-go.sh
chmod +x ./warp-go.sh
./warp-go.sh