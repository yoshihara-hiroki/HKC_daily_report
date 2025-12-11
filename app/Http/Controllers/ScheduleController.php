<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Models\Group;
use App\Models\Vehicle;
use App\Models\VehicleReservation;
use App\Models\MeetingRoom;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class ScheduleController extends Controller
{
    /**
     * 行先予定一覧を表示
     */
    public function index()
    {
        // 自分の予定を取得（新しい順）
        $schedules = auth()->user()->schedules()
            ->orderBy('schedule_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(20);

        return view('schedules.index', compact('schedules'));
    }

    /**
     * 新規作成フォームを表示
     */
    public function create(Request $request)
    {
        // パラメータから日付を取得（デフォは今日）
        $defaultDate = $request->input('date', date('Y-m-d'));

        // 有効な社用車と会議室を取得
        $vehicles = Vehicle::active()->orderBy('id')->get();
        $meetingRooms = MeetingRoom::active()->orderBy('id')->get();

        return view('schedules.create', compact('defaultDate', 'vehicles', 'meetingRooms'));
    }

    /**
     * 新規登録処理
     */
    public function store(StoreScheduleRequest $request)
    {
        Gate::authorize('create', Schedule::class);

        $validated = $request->validated();

        $schedule = $request->user()->schedules()->create($validated);

        // 社用車の予約処理
        if ($request->boolean('is_vehicle_reservation') && isset($validated['vehicle_id'])) {
            $schedule->vehicleReservation()->create([
                'vehicle_id' => $validated['vehicle_id'],
                'user_id' => $request->user()->id,
                'reservation_date' => $schedule->schedule_date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ]);
        }

        //会議室の予約処理
        if ($request->boolean('is_meeting_room') && isset($validated['meeting_room_id'])) {
            $schedule->meetingRoomReservation()->create([
                'meeting_room_id' => $validated['meeting_room_id'],
                'user_id' => $request->user()->id,
                'reservation_date' => $schedule->schedule_date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ]);
        }

        return redirect()->route('schedules.index')
            ->with('success', '行先予定を登録しました。');
    }

    /**
     * 編集フォームを表示
     */
    public function edit(Schedule $schedule)
    {
        Gate::authorize('update', $schedule);

        // 有効な社用車と会議室を取得
        $vehicles = Vehicle::active()->orderBy('id')->get();
        $meetingRooms = MeetingRoom::active()->orderBy('id')->get();

        return view('schedules.edit', compact('schedule', 'vehicles', 'meetingRooms'));
    }

    /**
     * 更新処理
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        Gate::authorize('update', $schedule);

        $validated = $request->validated();
        $schedule->update($validated);

        // 社用車予約の処理
        if ($request->boolean('is_vehicle_reservation') && isset($validated['vehicle_id'])) {

            $reservationData = [
                'vehicle_id' => $validated['vehicle_id'],
                'user_id' => $schedule->user_id,
                'reservation_date' => $schedule->schedule_date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ];

            if ($schedule->vehicleReservation) {
                // 既に予約がある場合は更新
                $schedule->vehicleReservation->update($reservationData);
            } else {
                // 新規作成
                $schedule->vehicleReservation()->create($reservationData);
            }
        } else {
            // チェックが外れていたら既存の予約を削除
            if ($schedule->vehicleReservation) {
                $schedule->vehicleReservation->delete();
            }
        }

        if ($request->boolean('is_meeting_room') && isset($validated['meeting_room_id'])) {
            $meetingData = [
                'meeting_room_id' => $validated['meeting_room_id'],
                'user_id' => $schedule->user_id,
                'reservation_date' => $schedule->schedule_date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ];

            if ($schedule->meetingRoomReservation) {
                // 既に予約がある場合は更新
                $schedule->meetingRoomReservation->update($meetingData);
            } else {
                // 新規作成
                $schedule->meetingRoomReservation()->create($meetingData);
            }
        } else {
            // チェックが外れていたら既存の予約を削除
            if ($schedule->meetingRoomReservation) {
                $schedule->meetingRoomReservation->delete();
            }
        }

        return redirect()->route('schedules.index')
            ->with('success', '行先予定を更新しました。');
    }

    /**
     * 削除処理
     */
    public function destroy(Schedule $schedule)
    {
        Gate::authorize('delete', $schedule);

        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', '行先予定を削除しました。');
    }

    /**
     * 全社員の予定をカレンダー表示
     */
    public function calendar(Request $request)
    {
        // 表示する年月
        $currentDate = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::now();

        // 選択されたグループID
        $selectedGroupId = $request->input('group_id');

        // カレンダー範囲
        $startDate = $currentDate->copy()->startOfMonth()->startOfWeek(CarbonInterface::SUNDAY);
        $endDate = $currentDate->copy()->endOfMonth()->endOfWeek(CarbonInterface::SUNDAY);

        // クエリの構築
        $query = Schedule::with(['user', 'vehicleReservation.vehicle', 'meetingRoomReservation.meetingRoom']);

        // グループ絞り込み
        if ($selectedGroupId) {
            $query->whereHas('user.groups', function ($q) use ($selectedGroupId) {
                $q->where('groups.id', $selectedGroupId);
            });
        }

        // 期間とソート
        $schedules = $query->whereBetween('schedule_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('start_time')
            ->get();

        // Alpine.js（カレンダー）で扱いやすいようにデータを整形
        $events = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'user_id' => $schedule->user_id,
                'title' => $schedule->destination,
                'start' => Carbon::parse($schedule->schedule_date)->format('Y-m-d'),
                'time' => $schedule->start_time ? $schedule->start_time->format('H:i') : '',
                'end_time' => $schedule->end_time ? $schedule->end_time->format('H:i') : null,
                'user_name' => $schedule->user->name,

                // Web会議情報
                'is_web_meeting' => $schedule->is_web_meeting,
                'meeting_type' => $schedule->meeting_type,

                // 社用車情報
                'vehicle_name' => $schedule->vehicleReservation && $schedule->vehicleReservation->vehicle
                    ? $schedule->vehicleReservation->vehicle->name
                    : null,

                // 会議室情報
                'meeting_room_name' => $schedule->meetingRoomReservation && $schedule->meetingRoomReservation->meetingRoom
                    ? $schedule->meetingRoomReservation->meetingRoom->name
                    : null,
            ];
        });

        // グループ一覧を取得
        $groups = Group::all();

        return view('schedules.calendar', compact(
            'currentDate',
            'startDate',
            'endDate',
            'events',
            'groups',
            'selectedGroupId'
        ));
    }
}
