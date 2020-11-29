@extends('mail.layout')
@section('content')
<tr>
    <td>
        <table bgcolor="#3490dc" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #e0e0e0;border-bottom:0;border-top-left-radius:3px;border-top-right-radius:3px">
            <tbody>
                <tr>
                    <td height="72px" colspan="3"></td>
                </tr>
                <tr>
                    <td width="32px"></td>
                    <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:24px;color:#ffffff;line-height:1.25">
                        Chấp nhận đăng ký chủ trọ
                    </td><td width="32px"></td>
                </tr>
                <tr>
                    <td height="18px" colspan="3"></td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td>
        <table bgcolor="#FAFAFA" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #f0f0f0;border-bottom:1px solid #c0c0c0;border-top:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px">
            <tbody>
                <tr height="16px">
                    <td width="32px" rowspan="3"></td>
                    <td></td>
                    <td width="32px" rowspan="3"></td>
                </tr>
                <tr>
                    <td>
                        <p>Thân gửi: {{ $receiver->full_name }}</p>
                        <p>Chúng tôi đã nhận được đơn đăng ký tài khoản chủ trọ trên hệ thống {{ env('APP_NAME') }} của bạn.
                            Qua quá trình xác thực thông tin, chúng tối đã chấp nhận đơn đăng ký của bạn</p>
                        <p>Hãy bắt đầu quản lý nhà/dãy trọ của bạn trên {{ env('APP_NAME') }} ngay</p>
                        <div style="padding-top: 32px;text-align: center;">
                            <a href="{{ env('APP_URL') }}" style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#3490dc;border-radius:5px;min-width:90px" target="_blank">
                                Đi tới trang
                            </a>
                        </div>
                        <p>Trân trọng!</p>
                        <p>{{ env('APP_NAME') }} team</p>
                    </td>
                </tr>
                <tr height="32px"></tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection